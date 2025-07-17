<?php

return [
    'url_front' =>  env('AUTH_URL_FRONT', 'https://autenticador-dev.hlog.mdh.gov.br'),
    'url_api' =>  env('AUTH_URL_API', 'https://api-autenticador-dev.hlog.mdh.gov.br/api'),
    'client_id' => env('AUTH_CLIENT_ID', 'projeto-base-local'),
    'client_secret' => env('AUTH_CLIENT_SECRET', '123456'),
    'sigla' => env('AUTH_SIGLA', 'projeto-base-local'),
    'url_logout' => env('AUTH_URL_LOGOUT', 'https://autenticador-dev.hlog.mdh.gov.br/logout')
];