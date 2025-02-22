<?php

namespace Hoks\Embeddings\Commands;

use Illuminate\Console\Command;
use Hoks\Embeddings\Models\Vector;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;


class ImportItems extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'items:import-in-mongodb';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import items in mongodb database';

    public function handle()
    {

        $importItems = self::getImportItems();
        
        //if import query parameters are not set, stop executing command
        if(!$importItems){
            $this->info('No items query data, please set parameters');
            return;
        }

        //progress bar
        $bar = $this->output->createProgressBar($importItems->count());

        foreach($importItems as $item) {
            try{
                $dataNormalization = $this->dataNormalization($item);
                if($dataNormalization){
                    $entity = new Vector();
                    $entity->fill($dataNormalization);
                    $entity->save();
                    $bar->advance();
                }
            }catch(\Exception $e){
                Log::info('FIRST_IMPORT',[$e->getMessage()]);
            }
            
        }

        $bar->finish();
        $this->info("Finished importing items vectors");

    }

    /**
     * this method prepares data for import in database
     * we need item_id and vector
     */
    public function dataNormalization($data) {
        $finalData = [];

        $columns = config('embeddings.text_columns');
        $text = '';
        foreach($columns as $column){
            $value = $data->$column;
            if(!empty($value)){
                if(config('embeddings.remove_tags')){
                    //if we need to remove tags from text (if text is in html)
                    $value = strip_tags($value);
                }
                //add dot at the end if needed
                if(str_ends_with($value,'.')){
                    $concatValue = '';
                }else{
                    $concatValue = '. ';
                }
                $text .= $value.$concatValue;
            }
        }
        // remove \r\n
        if(config('embeddings.remove_r_n')){
            $text = str_replace(array("\r", "\n"), '', $text); //removes \r\n
        }
        $text = html_entity_decode($text, ENT_QUOTES, 'UTF-8');//utf-8 decoding

        //get embedding from openai
        $client = \OpenAIAPI::client('embeddings',30,config('embeddings.embedding_model'));
        $vector = $client->embedding('petar petrovic njegos');
        
        $finalData['item_id'] = $data->id;
        $finalData['vector'] = $vector;
        
        return $finalData;
    }

    /**
     * get import items based on config data
     */
    public static function getImportItems(){
        $tableName = config('embeddings.items_table_name');
        if(isset($tableName) && !empty($tableName)){
            $importItems = \DB::table($tableName);
        }else{
           //if table name is empty, give info to user
            return false;
        }

        $queryArray = config('embeddings.inital_items_query');
        if(isset($queryArray) && !empty($queryArray)){
            foreach($queryArray as $key => $queryPart){
                //where query
                if($key == 'where'){
                    $importItems = $importItems->where($queryPart[0],$queryPart[1],$queryPart[2]);
                //order by query
                }elseif($key == 'order_by'){
                    $importItems = $importItems->orderBy($queryPart[0],$queryPart[1]);
                //limit query
                }elseif($key == 'limit'){
                    $importItems = $importItems->orderBy($queryPart[0],$queryPart[1]);
                }
            }
        }else{
           //if query array is empty, give info to user
           return false;
        }
        
        $importItems = $importItems->get();
        
        return $importItems;
    }

}