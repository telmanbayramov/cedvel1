<?php

return [

    'paths' => ['api/*', 'sanctum/csrf-cookie', '*'],

    'allowed_methods' => ['*'],

    'allowed_origins' => ['*'], // veya ['https://your-domain.com'] gibi spesifik domainler

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'], // veya ['Content-Type', 'Authorization'] gibi sp`esifik başlıklar

    'exposed_headers' => [],

    'max_age' => 3600, // Tarayıcı önbelleği için (isteğe bağlı)

    'supports_credentials' => true, // Kimlik bilgileri gerekliyse true yapın

]; 

