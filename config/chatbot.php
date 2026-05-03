<?php

return [
    /*
    |--------------------------------------------------------------------------
    | GROQ Configuration (free, fast, OpenAI-compatible)
    |--------------------------------------------------------------------------
    */
    'groq' => [
        'api_key'  => env('GROQ_API_KEY'),
        'endpoint' => 'https://api.groq.com/openai/v1/chat/completions',
        'model'    => env('GROQ_MODEL', 'llama-3.1-8b-instant'),
        'timeout'  => 20,
    ],
];

