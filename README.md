# Embeddings

For provided items, creates vectors using OpenAI embeddings API, and then performs contextual search using FAISS.

## Instalation

Install package
```bash
    composer require davor/embeddings
```
Publish config
```bash
    php artisan vendor:publish --tag=config --provider="Hoks\Embeddings\EmbeddingsServiceProvider"
```
Set up config/embeddings.php

Run command to create python script in storage/app
```bash
    php artisan create:python-script
```