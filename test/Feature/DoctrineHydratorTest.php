<?php

namespace ApiSkeletonsTest\Laravel\Hal\Doctrine\Feature;

use ApiSkeletons\Laravel\HAL\Resource;
use ApiSkeletonsTest\Laravel\HAL\Doctrine\Entity;
use ApiSkeletonsTest\Laravel\HAL\Doctrine\HAL\HydratorManager;
use ApiSkeletonsTest\Laravel\HAL\Doctrine\TestCase;
use ApiSkeletonsTest\Laravel\HAL\Doctrine\Trait\PopulateData;
use Doctrine\ORM\EntityManager;

final class DoctrineHydratorTest extends TestCase
{
    use PopulateData;

    private HydratorManager $hydratorManager;
    private EntityManager $entityManager;

    public function setUp(): void
    {
        parent::setUp();
        $this->entityManager = $this->createDatabase();
        $this->populateData();
        $this->hydratorManager = new HydratorManager();
    }

    public function testPopulateData(): void
    {
        $artist = $this->entityManager->getRepository(Entity\Artist::class)
            ->find(1);

        $this->assertEquals('Grateful Dead', $artist->getName());
    }

    public function testTopEntityState(): void
    {
        $hydratorManager = new HydratorManager();

        $artist = $this->entityManager->getRepository(Entity\Artist::class)
            ->find(1);

        $hal = $hydratorManager->extract($artist)->toArray();

        $this->assertEquals(1, $hal['id']);
        $this->assertEquals('Grateful Dead', $hal['name']);
    }

    public function testTopEntitySelfLink(): void
    {
        $artist = $this->entityManager->getRepository(Entity\Artist::class)
            ->find(1);
        $hal = $this->hydratorManager->extract($artist)->toArray();
        $this->assertEquals('http://localhost/artist/1', $hal['_links']['self']['href']);

        $artist = $this->entityManager->getRepository(Entity\Artist::class)
            ->find(2);
        $hal = $this->hydratorManager->extract($artist)->toArray();
        $this->assertEquals('http://localhost/artist/2', $hal['_links']['self']['href']);
    }


    public function testMidEntity(): void
    {
        $artist = $this->entityManager->getRepository(Entity\Performance::class)
            ->find(1);
        $hal = $this->hydratorManager->extract($artist)->toArray();

        print_r($hal);die();

        $this->assertEquals('http://localhost/artist/1', $hal['_links']['self']['href']);
    }
}
