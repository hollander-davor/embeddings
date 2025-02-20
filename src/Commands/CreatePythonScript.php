<?php

namespace Hoks\Embeddings\Commands;

use Hoks\Embeddings\Models\Vector;
use Illuminate\Console\Command;

class CreatePythonScript extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:python-script';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Put python script into storage/app';

    

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $packagePath = 'davor/embeddings/scripts';
        $sourceFile = 'python_script.py';

        $sourceFilePath = base_path($packagePath.'/'.$sourceFile);

        //get contents from package
        if(!file_exists($sourceFilePath)){
            $this->error('Source file does not exist!');
            return;
        }
        $content = file_get_contents($sourceFilePath);

        //put them in storage/app
        $filePath = storage_path('app/'.$sourceFile);
        file_put_contents($filePath,$content);

        $this->info('Python file created at path: '.$filePath);
    }
}
