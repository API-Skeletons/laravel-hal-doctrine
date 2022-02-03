# Laravel HAL Doctrine

[![Build Status](https://github.com/API-Skeletons/laravel-hal-doctrine/actions/workflows/continuous-integration.yml/badge.svg)](https://github.com/API-Skeletons/laravel-hal-doctrine/actions/workflows/continuous-integration.yml?query=branch%3Amain)
[![Code Coverage](https://codecov.io/gh/API-Skeletons/laravel-hal-doctrine/branch/main/graphs/badge.svg)](https://codecov.io/gh/API-Skeletons/laravel-hal-doctrine/branch/main)
[![PHP Version](https://img.shields.io/badge/PHP-8.0%2b-blue)](https://img.shields.io/badge/PHP-7.3%20to%208.0%2b-blue)
[![Laravel Version](https://img.shields.io/badge/Laravel-8.x%2b-red)](https://img.shields.io/badge/Laravel-5.7%20to%208.x-red)
[![Total Downloads](https://poser.pugx.org/api-skeletons/laravel-hal-doctrine/downloads)](//packagist.org/packages/api-skeletons/laravel-hal-doctrine)
[![License](https://poser.pugx.org/api-skeletons/laravel-hal-doctrine/license)](//packagist.org/packages/api-skeletons/laravel-hal-doctrine)

This library provides a hydrator for [laravel-hal](https://github.com/API-Skeletons/laravel-hal)
for Doctrine.  Instead of manually creating every hydrator for your entities, this library
will introspect an entity and generate the HAL for it including links to 
other entities and collections for one to many relationships and embedding many to one and one to one
entities.

A grouped minimal configuration file allows for excluded fields and associations and
configures the routes for each entity.


## Use

### Create a hydrator manager

```php
namespace App\HAL;

use ApiSkeletons\Laravel\HAL\HydratorManager as HALHydratorManager;

final class HydratorManager extends HALHydratorManager
{
    public function __construct() 
    {
        $this->classHydrators = [
            // This wildcard entry is used as an example and may not be exactly what you need
            '*' => \ApiSkeletons\Laravel\HAL\Doctrine\DoctrineHydrator::class,
        ];
    }
}
```

### Extract an entity
```php
use App\Hal\HydratorManager;

public function fetch(Entity\Artist $artist): array
{
    return (new HydratorManager())->extract($artist)->toArray();
}
```

will result in a HAL response like 
```json
{
  "_links": {
    "self": {
      "href": "https://website/artist/1"
    },
    "albums": {
      "href": "https://website/album?filter[artist]=1"
    }
  },
  "id": 1,
  "name": "Grateful Dead"
}
```


## Configuration

A `hal-doctrine.php` configuration file is required.  Publish the included config to your project: 

```sh
php artisan vendor:publish --tag=config
```

```php
$config = [
    'default' => [
        'entityManager' => EntityManager::class,
        'routeNamePatterns' => [
            'entity' => 'api.{entityName}::fetch',
            'collection' => 'api.{entityName}::fetchAll',
        ],
        // All entities configuration is optional
        'entities' => [
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
        ],
    ],
];
```

### Doctrine\Laminas\Hydrator\DoctrineObject

The Laminas Hydrator is used by this library to extract data directly from entities.  You must add this configuration
to your `doctrine.php` configuration file:

```php
'custom_hydration_modes' = [
    'hal-doctrine' => \Doctrine\Laminas\Hydrator\DoctrineObject::class,
],
```


### Naming Strategy

The default naming strategy uses the Inflector's `urlize()` method to change 'associationName' into 'association-name'.
If this is not the way you want to name your relationsihps or routes then create your own naming strategy and assign
it in the config file.


## Route naming

When using the `routeNamePatterns` to create a route name, the entity name becomes `$namingStrategy->route($entityName)`
such as `api.short-name::fetch` according to the example configuration.


## Filtering Collections

For extracted related collections, links will be created with filter options compatible with
`ApiSkeletons\Doctrine\QueryBuilder\Filter\Applicator` to filter the collection data to just the extracted entity.
For example

```json
  "_links": {
    ...
    "album": {
      "href": "https://website/album?filter[artist]=1"
    }
```

The link to the Album will be filtered where `album.artist = 1`.  In order to process these URLs in your application, 
implement `ApiSkeletons\Doctrine\QueryBuilder\Filter\Applicator` in your controller action:

```php
use ApiSkeletons\Doctrine\QueryBuilder\Filter\Applicator;

public function fetchAll(EntityManager $entityManager): array
{
    $applicator = new Applicator($entityManager, Entity\Album::class);
    $queryBuilder = $applicator($_REQUEST['filter']);
    
    return (new HydratorManager())
        ->paginate('albums', collect($queryBuilder->getQuery()->getResult()))->toArray();
}
```

See the [documentation for doctrine-querybuilder-filter](https://github.com/API-Skeletons/doctrine-querybuilder-filter#doctrine-querybuilder-filter)
for more detailed examples.


## Multiple Object Managers

To configure a hydrator for other than the `default` configuration section, extend the Doctrine Hydrator
```php
class SecondDoctrineHydrator extends ApiSkeletons\Laravel\HAL\Doctrine\DoctrineHydrator
{
    protected string $configurationSection = 'secondary';
}
```

Then in your `hal-doctrine.php` configuration file, create a new section titled `secondary` and set the 
`entityManager` to your second Entity Manager.
