<?php

return [
    'default' => [
        'entityManager' => \Doctrine\ORM\EntityManager::class,
        'namingStrategy' => \ApiSkeletons\Laravel\HAL\Doctrine\NamingStrategy\DefaultNamingStrategy::class,
        'routeNamePatterns' => [
            'entity' => 'api.{entityName}::fetch',
            'collection' => 'api.{entityName}::fetchAll',
        ],
        'entities' => [
            \ApiSkeletonsTest\Laravel\HAL\Doctrine\Entity\Artist::class => [
                'exclude' => [
//                    'name',
                ],
                'routesNames' => [
                    'entity' => 'artist::fetch',
                    'collection' => 'artist::fetchAll',
                ],
            ],
            \ApiSkeletonsTest\Laravel\HAL\Doctrine\Entity\Performance::class => [
                // Override route patterns
                'routesNames' => [
                    'entity' => 'performance::fetch',
                    'collection' => 'performance::fetchAll',
                ],
                // List of fields and assocations to exclude
                'exclude' => [
                    'recordings',
                ],
            ],
        ],
    ],
];
