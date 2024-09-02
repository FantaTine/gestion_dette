<?php

return [
    'openapi' => '3.0.0',
    'info' => [
        'title' => 'API Documentation',
        'version' => '1.0.0',
    ],
    'servers' => [
        [
            'url' => env('APP_URL') . '/api/v1',
            'description' => 'API v1 Server',
        ],
    ],
    'paths' => [
        '/login' => [
            'post' => [
                'summary' => 'Connexion',
                'description' => 'Connexion de l\'utilisateur',
                'requestBody' => [
                    'required' => true,
                    'content' => [
                        'application/json' => [
                            'schema' => [
                                'type' => 'object',
                                'properties' => [
                                    'login' => ['type' => 'string'],
                                    'password' => ['type' => 'string'],
                                ],
                            ],
                        ],
                    ],
                ],
                'responses' => [
                    '200' => [
                        'description' => 'Connexion réussie',
                        'content' => [
                            'application/json' => [
                                'schema' => [
                                    'type' => 'object',
                                    'properties' => [
                                        'message' => ['type' => 'string'],
                                        'user' => ['type' => 'object'],
                                        'token' => ['type' => 'string'],
                                    ],
                                ],
                            ],
                        ],
                    ],
                    '401' => [
                        'description' => 'Informations d\'identification invalides',
                        'content' => [
                            'application/json' => [
                                'schema' => [
                                    'type' => 'object',
                                    'properties' => [
                                        'message' => ['type' => 'string'],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ],
        '/api/v1/users' => [
            'post' => [
                'summary' => 'Créer un utilisateur',
                'description' => 'Création d\'un nouvel utilisateur',
                'requestBody' => [
                    'required' => true,
                    'content' => [
                        'application/json' => [
                            'schema' => [
                                '$ref' => '#/components/schemas/UserRequest',
                            ],
                        ],
                    ],
                ],
                'responses' => [
                    '201' => [
                        'description' => 'Utilisateur créé avec succès',
                        'content' => [
                            'application/json' => [
                                'schema' => [
                                    '$ref' => '#/components/schemas/User',
                                ],
                            ],
                        ],
                    ],
                    '409' => [
                        'description' => 'Un utilisateur avec ce login ou ce numéro de téléphone existe déjà',
                        'content' => [
                            'application/json' => [
                                'schema' => [
                                    'type' => 'object',
                                    'properties' => [
                                        'message' => ['type' => 'string'],
                                    ],
                                ],
                            ],
                        ],
                    ],
                    '500' => [
                        'description' => 'Erreur lors de la création de l\'utilisateur',
                        'content' => [
                            'application/json' => [
                                'schema' => [
                                    'type' => 'object',
                                    'properties' => [
                                        'message' => ['type' => 'string'],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            'get' => [
                'summary' => 'Liste des utilisateurs',
                'description' => 'Récupération de la liste des utilisateurs',
                'parameters' => [
                    [
                        'name' => 'role',
                        'in' => 'query',
                        'schema' => [
                            'type' => 'string',
                        ],
                    ],
                    [
                        'name' => 'active',
                        'in' => 'query',
                        'schema' => [
                            'type' => 'string',
                            'enum' => ['oui', 'non'],
                        ],
                    ],
                ],
                'responses' => [
                    '200' => [
                        'description' => 'Utilisateurs récupérés avec succès',
                        'content' => [
                            'application/json' => [
                                'schema' => [
                                    'type' => 'array',
                                    'items' => [
                                        '$ref' => '#/components/schemas/User',
                                    ],
                                ],
                            ],
                        ],
                    ],
                    '204' => [
                        'description' => 'Aucun utilisateur trouvé',
                    ],
                ],
            ],
        ],
        '/api/v1/clients' => [
            'post' => [
                'summary' => 'Créer un client',
                'description' => 'Création d\'un nouveau client',
                'requestBody' => [
                    'required' => true,
                    'content' => [
                        'application/json' => [
                            'schema' => [
                                '$ref' => '#/components/schemas/ClientRequest',
                            ],
                        ],
                    ],
                ],
                'responses' => [
                    '201' => [
                        'description' => 'Client créé avec succès',
                        'content' => [
                            'application/json' => [
                                'schema' => [
                                    '$ref' => '#/components/schemas/Client',
                                ],
                            ],
                        ],
                    ],
                    '500' => [
                        'description' => 'Erreur lors de la création du client',
                        'content' => [
                            'application/json' => [
                                'schema' => [
                                    'type' => 'object',
                                    'properties' => [
                                        'message' => ['type' => 'string'],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            'get' => [
                'summary' => 'Liste des clients',
                'description' => 'Récupération de la liste des clients',
                'parameters' => [
                    [
                        'name' => 'telephone',
                        'in' => 'query',
                        'schema' => [
                            'type' => 'string',
                        ],
                    ],
                    [
                        'name' => 'comptes',
                        'in' => 'query',
                        'schema' => [
                            'type' => 'string',
                            'enum' => ['oui', 'non'],
                        ],
                    ],
                    [
                        'name' => 'active',
                        'in' => 'query',
                        'schema' => [
                            'type' => 'string',
                            'enum' => ['oui', 'non'],
                        ],
                    ],
                ],
                'responses' => [
                    '200' => [
                        'description' => 'Clients récupérés avec succès',
                        'content' => [
                            'application/json' => [
                                'schema' => [
                                    'type' => 'array',
                                    'items' => [
                                        '$ref' => '#/components/schemas/Client',
                                    ],
                                ],
                            ],
                        ],
                    ],
                    '204' => [
                        'description' => 'Aucun client trouvé',
                    ],
                ],
            ],
        ],
        '/api/v1/articles' => [
            'post' => [
                'summary' => 'Créer un article',
                'description' => 'Création d\'un nouvel article',
                'requestBody' => [
                    'required' => true,
                    'content' => [
                        'application/json' => [
                            'schema' => [
                                '$ref' => '#/components/schemas/ArticleRequest',
                            ],
                        ],
                    ],
                ],
                'responses' => [
                    '201' => [
                        'description' => 'Article créé avec succès',
                        'content' => [
                            'application/json' => [
                                'schema' => [
                                    '$ref' => '#/components/schemas/Article',
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            'get' => [
                'summary' => 'Liste des articles',
                'description' => 'Récupération de la liste des articles',
                'parameters' => [
                    [
                        'name' => 'disponible',
                        'in' => 'query',
                        'schema' => [
                            'type' => 'string',
                            'enum' => ['oui', 'non'],
                        ],
                    ],
                ],
                'responses' => [
                    '200' => [
                        'description' => 'Articles récupérés avec succès',
                        'content' => [
                            'application/json' => [
                                'schema' => [
                                    'type' => 'array',
                                    'items' => [
                                        '$ref' => '#/components/schemas/Article',
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ],
        '/api/v1/articles/{id}/quantity' => [
            'patch' => [
                'summary' => 'Mettre à jour la quantité d\'un article',
                'description' => 'Mise à jour de la quantité d\'un article',
                'parameters' => [
                    [
                        'name' => 'id',
                        'in' => 'path',
                        'required' => true,
                        'schema' => [
                            'type' => 'integer',
                        ],
                    ],
                ],
                'requestBody' => [
                    'required' => true,
                    'content' => [
                        'application/json' => [
                            'schema' => [
                                'type' => 'object',
                                'properties' => [
                                    'qte' => [
                                        'type' => 'integer',
                                        'description' => 'La quantité à ajouter',
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
                'responses' => [
                    '200' => [
                        'description' => 'Quantité de l\'article mise à jour avec succès',
                        'content' => [
                            'application/json' => [
                                'schema' => [
                                    '$ref' => '#/components/schemas/Article',
                                ],
                            ],
                        ],
                    ],
                    '404' => [
                        'description' => 'Article non trouvé',
                        'content' => [
                            'application/json' => [
                                'schema' => [
                                    'type' => 'object',
                                    'properties' => [
                                        'message' => ['type' => 'string'],
                                    ],
                                ],
                            ],
                        ],
                    ],
                    '500' => [
                        'description' => 'Erreur lors de la mise à jour de la quantité',
                        'content' => [
                            'application/json' => [
                                'schema' => [
                                    'type' => 'object',
                                    'properties' => [
                                        'message' => ['type' => 'string'],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ],
        '/api/v1/articles/stock' => [
            'post' => [
                'summary' => 'Mettre à jour le stock',
                'description' => 'Mise à jour du stock des articles',
                'requestBody' => [
                    'required' => true,
                    'content' => [
                        'application/json' => [
                            'schema' => [
                                'type' => 'object',
                                'properties' => [
                                    'articles' => [
                                        'type' => 'array',
                                        'items' => [
                                            'type' => 'object',
                                            'properties' => [
                                                'id' => ['type' => 'integer'],
                                                'qte' => ['type' => 'integer'],
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
                'responses' => [
                    '200' => [
                        'description' => 'Processus de mise à jour du stock terminé',
                        'content' => [
                            'application/json' => [
                                'schema' => [
                                    'type' => 'object',
                                    'properties' => [
                                        'success' => [
                                            'type' => 'array',
                                            'items' => [
                                                '$ref' => '#/components/schemas/Article',
                                            ],
                                        ],
                                        'error' => [
                                            'type' => 'array',
                                            'items' => [
                                                'type' => 'integer',
                                        ],
                                    ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                    '500' => [
                        'description' => 'Erreur lors de la mise à jour du stock',
                        'content' => [
                            'application/json' => [
                                'schema' => [
                                    'type' => 'object',
                                    'properties' => [
                                        'message' => ['type' => 'string'],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ],
        '/api/v1/articles/title' => [
            'post' => [
                'summary' => 'Récupérer un article par titre',
                'description' => 'Récupération d\'un article par son titre',
                'requestBody' => [
                    'required' => true,
                    'content' => [
                        'application/json' => [
                            'schema' => [
                                'type' => 'object',
                                'properties' => [
                                    'title' => ['type' => 'string'],
                                ],
                            ],
                        ],
                    ],
                ],
                'responses' => [
                    '200' => [
                        'description' => 'Article récupéré avec succès',
                        'content' => [
                            'application/json' => [
                                'schema' => [
                                    '$ref' => '#/components/schemas/Article',
                                ],
                            ],
                        ],
                    ],
                    '404' => [
                        'description' => 'Article non trouvé',
                        'content' => [
                            'application/json' => [
                                'schema' => [
                                    'type' => 'object',
                                    'properties' => [
                                        'message' => ['type' => 'string'],
                                    ],
                                ],
                            ],
                        ],
                    ],
                    '500' => [
                        'description' => 'Erreur lors de la récupération de l\'article',
                        'content'=> [
                            'application/json' => [
                                'schema' => [
                                    'type' => 'object',
                                    'properties' => [
                                        'message' => ['type' => 'string'],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
    'components' => [
        'schemas' => [
            'UserRequest' => [
                'type' => 'object',
                'properties' => [
                    'nom' => ['type' => 'string'],
                    'prenom' => ['type' => 'string'],
                    'telephone' => ['type' => 'string'],
                    'login' => ['type' => 'string'],
                    'password' => ['type' => 'string'],
                    'role_id' => ['type' => 'integer'],
                    'active' => ['type' => 'boolean'],
                    'photo' => ['type' => 'string', 'format' => 'binary'],
                ],
            ],
            'User' => [
                'type' => 'object',
                'properties' => [
                    'id' => ['type' => 'integer'],
                    'nom' => ['type' => 'string'],
                    'prenom' => ['type' => 'string'],
                    'telephone' => ['type' => 'string'],
                    'login' => ['type' => 'string'],
                    'role' => ['type' => 'string'],
                    'active' => ['type' => 'boolean'],
                    'photo' => ['type' => 'string'],
                ],
            ],
            'ClientRequest' => [
                'type' => 'object',
                'properties' => [
                    'nom' => ['type' => 'string'],
                    'prenom' => ['type' => 'string'],
                    'telephone' => ['type' => 'string'],
                    'adresse' => ['type' => 'string'],
                    'email' => ['type' => 'string'],
                    'user' => [
                        'type' => 'object',
                        'properties' => [
                            'nom' => ['type' => 'string'],
                            'prenom' => ['type' => 'string'],
                            'telephone' => ['type' => 'string'],
                            'login' => ['type' => 'string'],
                            'password' => ['type' => 'string'],
                            'role_id' => ['type' => 'integer'],
                        ],
                    ],
                ],
            ],
            'Client' => [
                'type' => 'object',
                'properties' => [
                    'id' => ['type' => 'integer'],
                    'nom' => ['type' => 'string'],
                    'prenom' => ['type' => 'string'],
                    'telephone' => ['type' => 'string'],
                    'adresse' => ['type' => 'string'],
                    'email' => ['type' => 'string'],
                    'user' => [
                        'type' => 'object',
                        'properties' => [
                            'id' => ['type' => 'integer'],
                            'nom' => ['type' => 'string'],
                            'prenom' => ['type' => 'string'],
                            'telephone' => ['type' => 'string'],
                            'login' => ['type' => 'string'],
                            'role' => ['type' => 'string'],
                            'active' => ['type' => 'boolean'],
                        ],
                    ],
                ],
            ],
            'ArticleRequest' => [
                'type' => 'object',
                'properties' => [
                    'title' => ['type' => 'string'],
                    'description' => ['type' => 'string'],
                    'quantity' => ['type' => 'integer'],
                    'price' => ['type' => 'number'],
                ],
            ],
            'Article' => [
                'type' => 'object',
                'properties' => [
                    'id' => ['type' => 'integer'],
                    'title' => ['type' => 'string'],
                    'description' => ['type' => 'string'],
                    'quantity' => ['type' => 'integer'],
                    'price' => ['type' => 'number'],
                ],
            ],
        ],
    ],
];
