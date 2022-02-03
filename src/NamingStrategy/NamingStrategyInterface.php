<?php

declare(strict_types=1);

namespace ApiSkeletons\Laravel\HAL\Doctrine\NamingStrategy;

interface NamingStrategyInterface
{
    public function route(string $entityName): string;

    public function association(string $associationName): string;
}
