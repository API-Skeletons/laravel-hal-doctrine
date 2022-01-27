<?php

namespace ApiSkeletonsTest\Laravel\Doctrine\HAL;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\SchemaTool;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

abstract class TestCase extends OrchestraTestCase
{
    protected function getPackageProviders($app)
    {
        return [
            \LaravelDoctrine\ORM\DoctrineServiceProvider::class,
            \ApiSkeletons\Laravel\Doctrine\HAL\ServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']['doctrine.managers.default.paths'] = [
            __DIR__ . '/Entities'
        ];
    }

    protected function createDatabase(EntityManager $entityManager): EntityManager
    {
        $tool = new SchemaTool($entityManager);
        $tool->createSchema($entityManager->getMetadataFactory()->getAllMetadata());

        return $entityManager;
    }
}
