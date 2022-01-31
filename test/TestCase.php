<?php

namespace ApiSkeletonsTest\Laravel\HAL\Doctrine;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\SchemaTool;
use LaravelDoctrine\ORM\DoctrineServiceProvider;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

abstract class TestCase extends OrchestraTestCase
{
    protected function getPackageProviders($app)
    {
        return [
            DoctrineServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']['doctrine.managers.default.paths'] = [
            __DIR__ . '/config'
        ];

        $app['config']['doctrine.managers.default.namespaces'] = [
            'ApiSkeletonsTest\Laravel\HAL\Doctrine\Entity',
        ];

        $app['config']['doctrine.managers.default.meta'] = 'xml';
    }

    protected function createDatabase(EntityManager $entityManager): EntityManager
    {
        $tool = new SchemaTool($entityManager);
        $tool->createSchema($entityManager->getMetadataFactory()->getAllMetadata());

        return $entityManager;
    }
}
