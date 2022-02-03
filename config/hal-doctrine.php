<?php

return [
    'default' => [
        'entityManager' => \Doctrine\ORM\EntityManager::class,
        'namingStrategy' => \ApiSkeletons\Laravel\HAL\Doctrine\NamingStrategy\DefaultNamingStrategy::class,
        'routeNamePatterns' => [
            'entity' => 'api.{entityName}::fetch',
            'collection' => 'api.{entityName}::fetchAll',
        ],
        // All entities configuration is optional
        'entities' => [
            /*
            \App\ORM\Entity\Artist::class => [
                // Override route patterns
                'routesNames' => [
                    'entity' => 'artist::fetch',
                    'collection' => 'artist::fetchAll',
                ],
                // List of fields and associations to exclude
                'exclude' => [
                    'alias',
                ],
            ],
            */
        ],
    ],
];
