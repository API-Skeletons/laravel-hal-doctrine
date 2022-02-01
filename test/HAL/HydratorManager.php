<?php

namespace ApiSkeletonsTest\Laravel\HAL\Doctrine\HAL;

use ApiSkeletons\Laravel\HAL\Doctrine\DoctrineHydrator;
use ApiSkeletons\Laravel\HAL\HydratorManager as HALHydratorManager;
use ApiSkeletonsTest\Laravel\HAL\Doctrine\Entity;

final class HydratorManager extends HALHydratorManager
{
    public function __construct()
    {
        $this->classHydrators = [
            Entity\Artist::class => DoctrineHydrator::class,
            Entity\Performance::class => DoctrineHydrator::class,
        ];
    }
}
