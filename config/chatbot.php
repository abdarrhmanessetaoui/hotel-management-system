<?php

return [
    /*
    |--------------------------------------------------------------------------
    | OpenAI API Configuration
    |--------------------------------------------------------------------------
    |
    | Set OPENAI_API_KEY in your .env file to enable the AI-powered chatbot.
    | Without it, the system will use we smart rule-based fallback engine.
    |
    | OPENAI_MODEL: Recommended: gpt-3.5-turbo (cheaper) or gpt-4o (smarter)
    |
    */

    'openai_key'   => env('OPENAI_API_KEY', null),
    'openai_model' => env('OPENAI_MODEL', 'gpt-3.5-turbo'),
];
