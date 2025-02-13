<?php

namespace Hoks\Embeddings\Models;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Vector extends Eloquent {

    protected $connection = 'mongodb';
    protected $collection = 'vectors';
    protected $fillable = [
        'item_id',
        'vector'
    ];


}