<?php

namespace Hoks\Embeddings\Facades;

use Illuminate\Support\Facades\Facade;

class OpenAIAPI extends Facade{

    protected static function getFacadeAccessor()
    {
        return 'OpenAIAPI';
    }

}