<?php

namespace Hoks\Embeddings\Commands;

use Hoks\Embeddings\Models\Vector;
use Illuminate\Console\Command;

class SearchItems extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'search:items';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'For provided text, search trough items vectors';

    

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        // dd(shell_exec('/usr/local/python/bin/python3 -c "print(\'ajde\')"'));
        //get data
        $vectors = Vector::get();
        $vectorsArray = [];
        $idsArray = [];
        foreach($vectors as $vector){
            //prepare data
            $vectorArray = $vector->vector;
            $vectorsArray[] = $vectorArray;
            $idsArray[] = $vector->item_id;
        }
        //now we need to get search vector
        $client = \OpenAIAPI::client('embeddings',30,config('embeddings.embedding_model'));
        $searchVector = $client->embedding('hapsenja zbog palestinaca');
        
        $completeVectorData = [
            'vectors' => $vectorsArray,
            'searchVector' => $searchVector
        ];
        //put array into temporary file
        $tempFile = storage_path('app/temp_data.json');
        file_put_contents($tempFile,json_encode($completeVectorData));
        //script path
        $scriptPath = storage_path('app/python_script.py');
        //call python script
        $output = shell_exec('/usr/local/python/bin/python3 '.$scriptPath.' '.$tempFile);
        //delete temporary file
        if (file_exists($tempFile)) {
            unlink($tempFile);
        }
        $decodedOutput = json_decode($output);
        $itemsArray = $decodedOutput->indices[0];
        
        $final = [];
        foreach($itemsArray as $item){
            $final[] = $idsArray[$item];
        }
        dd($final);
    }
}
