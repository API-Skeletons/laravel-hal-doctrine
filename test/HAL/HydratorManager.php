<?php

namespace ApiSkeletonsTest\Laravel\HAL\Doctrine\HAL;

use ApiSkeletons\Laravel\HAL\Doctrine\DoctrineHydrator;
use ApiSkeletons\Laravel\HAL\HydratorManager as HALHydratorManager;

final class HydratorManager extends HALHydratorManager
{
    public function __construct()
    {
        $this->classHydrators = [
            Artist::class => DoctrineHydrator::class,
        ];
    }
}
