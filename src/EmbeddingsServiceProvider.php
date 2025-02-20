<?php

namespace Hoks\Embeddings;

use Illuminate\Support\ServiceProvider;
use Hoks\Embeddings\OpenAIAPI;
use Hoks\Embeddings\Commands\ImportItems;
use Hoks\Embeddings\Commands\SearchItems;
use Hoks\Embeddings\Commands\CreatePythonScript;

class EmbeddingsServiceProvider extends ServiceProvider{

    public function boot(){
        $this->publishes([
            __DIR__.'/config/embeddings.php' => config_path('embeddings.php')
        ],'config');
        if ($this->app->runningInConsole()) {
            $this->commands([
                ImportItems::class,
                SearchItems::class,
                CreatePythonScript::class
            ]);
        }
      
    }

    public function register(){
        $this->app->bind('OpenAIAPI',function(){
            return new OpenAIAPI();
        });
        $this->mergeConfigFrom(__DIR__.'/config/embeddings.php', 'embeddings');
    }
}