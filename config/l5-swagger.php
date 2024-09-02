<?php

return [
    'default' => 'default',
    'documentations' => [
        'default' => [
            'api' => [
                'title' => 'API Documentation',
            ],
            'routes' => [
                'api' => 'documentation',
                'docs' => 'documentation-json',
            ],
            'paths' => [
                'annotations' => base_path('config/swagger.php'),
                'docs' => base_path('storage/api-docs'),
                'docs_json' => 'storage/api-docs/api.json',
                'docs_yaml' => 'storage/api-docs/api.yaml',
                'excludes' => [''],
                'use_absolute_path' => true,
                'docs_json' => 'docs/swagger.json',
                'base' => env('L5_SWAGGER_BASE_PATH', null), // Ajoutez cette ligne
                'docs_uri' => 'docs/swagger.json',
                'ui' => 'docs/swagger',
            ],
            'constants' => [
                'SWAGGER_CONST_HOST' => env('APP_URL'),
            ],
        ],
    ],
    'defaults' => [
        'routes' => [
            'uri_prefix' => 'docs/swagger',
        ],
    ],
];

