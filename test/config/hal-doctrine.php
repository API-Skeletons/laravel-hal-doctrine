<?php

return [
    'default' => [
        'entityManager' => \Doctrine\ORM\EntityManager::class,
        'routePatterns' => [
            'entity' => 'api.{name}::fetch',
            'collection' => 'api.{name}::fetchAll',
        ],
        'entities' => [
            \ApiSkeletonsTest\Laravel\HAL\Doctrine\Entity\Artist::class => [
                'exclude' => [
//                    'name',
                ],
            ],
            \ApiSkeletonsTest\Laravel\HAL\Doctrine\Entity\Performance::class => [
                // Override route patterns
                'routes' => [
                    'entity' => 'performance::fetch',
                    'collection' => 'performance::fetchAll',
                ],
                // List of fields and assocations to exclude
                'exclude' => [
                    'alias',
                ],
            ],
        ],
    ],
];
