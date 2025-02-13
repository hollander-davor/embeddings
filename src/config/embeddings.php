<?php

return [
    //api key for openai
    'openai-api-key' => '',
    //chat gpt model to use (Note: quality of data may depend on model used)
    'ai_model' => 'gpt-4-turbo',
    //embeddings model
    'embedding_model' => 'text-embedding-3-small',
    //name of db table for items we want to make vectors for
    'items_table_name' => 'articles',
    //query for initial import of items for vectors generation
    //array of values that will be put into query
    'inital_items_query' => [
        'where' => ['published','=',1],
        'order_by' => ['publish_at','desc'],
    ],
    //column(s) from items table from which we take text
    'text_columns' => [
        'text'
    ],
    //if text_column has html structure, should it be removed
    'remove_tags' => true,
    
    
];