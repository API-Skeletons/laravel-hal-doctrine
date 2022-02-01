<?php

namespace ApiSkeletonsTest\Laravel\HAL\Doctrine;

use Doctrine\Laminas\Hydrator\DoctrineObject;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\SchemaTool;
use Illuminate\Support\Facades\Route;
use LaravelDoctrine\ORM\DoctrineServiceProvider;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

abstract class TestCase extends OrchestraTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        Route::get('artist', function() {
            return true;
        })->name('api.artist::fetchAll');
        Route::get('artist/{id}', function() {
            return true;
        })->name('api.artist::fetch');

        Route::get('performance', function() {
            return true;
        })->name('api.performance::fetchAll');
        Route::get('performance/{id}', function() {
            return true;
        })->name('api.performance::fetch');

        Route::get('recording', function() {
            return true;
        })->name('api.recording::fetchAll');
        Route::get('recording/{id}', function() {
            return true;
        })->name('api.recording::fetch');

    }

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

        $app['config']['hal-doctrine'] = include(__DIR__ . '/config/hal-doctrine.php');

        $app['config']['doctrine.custom_hydration_modes'] = [
            'hal-doctrine' => DoctrineObject::class,
        ];
    }

    protected function createDatabase(): EntityManager
    {
        $entityManager = app('em');
        $tool = new SchemaTool($entityManager);
        $tool->createSchema($entityManager->getMetadataFactory()->getAllMetadata());

        return $entityManager;
    }
}
