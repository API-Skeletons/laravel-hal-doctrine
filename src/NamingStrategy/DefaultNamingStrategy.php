<?php

declare(strict_types=1);

namespace ApiSkeletons\Laravel\HAL\Doctrine\NamingStrategy;

use Doctrine\Inflector\Inflector;
use Doctrine\Inflector\InflectorFactory;
use ReflectionClass;

class DefaultNamingStrategy implements NamingStrategyInterface
{
    protected Inflector $inflector;

    public function __construct()
    {
        $this->inflector = InflectorFactory::create()->build();
    }

    public function route(string $entityName): string
    {
        return $this->inflector
            ->urlize((new ReflectionClass($entityName))->getShortName());
    }

    public function association(string $associationName): string
    {
        return $this->inflector->urlize($associationName);
    }
}
