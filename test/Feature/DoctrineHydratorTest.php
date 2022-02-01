<?php

namespace ApiSkeletonsTest\Laravel\Hal\Doctrine\Feature;

use ApiSkeletonsTest\Laravel\HAL\Doctrine\Entity;
use ApiSkeletonsTest\Laravel\HAL\Doctrine\HAL\HydratorManager;
use ApiSkeletonsTest\Laravel\HAL\Doctrine\TestCase;
use ApiSkeletonsTest\Laravel\HAL\Doctrine\Trait\PopulateData;
use Doctrine\ORM\EntityManager;
use Illuminate\Support\Arr;

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

    public function testState(): void
    {
        $hydratorManager = new HydratorManager();

        $artist = $this->entityManager->getRepository(Entity\Artist::class)
            ->find(1);

        $hal = $hydratorManager->extract($artist)->toArray();

        $this->assertEquals(1, $hal['id']);
        $this->assertEquals('Grateful Dead', $hal['name']);
    }

    public function testSelfLink(): void
    {
        $artist = $this->entityManager->getRepository(Entity\Artist::class)
            ->find(1);
        $hal = $this->hydratorManager->extract($artist)->toArray();
        $this->assertEquals('http://localhost/artist/1', $hal['_links']['self']['href']);

        $artist = $this->entityManager->getRepository(Entity\Artist::class)
            ->find(2);
        $hal = $this->hydratorManager->extract($artist)->toArray();
        $this->assertEquals('http://localhost/artist/2', $hal['_links']['self']['href']);

        $performance = $this->entityManager->getRepository(Entity\Performance::class)
            ->find(1);
        $hal = $this->hydratorManager->extract($performance)->toArray();
        $this->assertEquals('http://localhost/performance/1', $hal['_links']['self']['href']);
    }

    public function testManyToOne(): void
    {
        $performance = $this->entityManager->getRepository(Entity\Performance::class)
            ->find(1);
        $hal = $this->hydratorManager->extract($performance)->toArray();

        $hal = Arr::dot($hal);

        $this->assertEquals('http://localhost/artist/1', $hal['_embedded.artist._links.self.href']);
    }

    public function testOneToMany(): void
    {
        $artist = $this->entityManager->getRepository(Entity\Artist::class)
            ->find(1);
        $hal = $this->hydratorManager->extract($artist)->toArray();

        $hal = Arr::dot($hal);

        $this->assertEquals('http://localhost/performance?filter[artist]=1', urldecode($hal['_links.performances.href']));
    }

    public function testOneToOneOwningSide(): void
    {
        $user = $this->entityManager->getRepository(Entity\User::class)
            ->find(1);
        $hal = $this->hydratorManager->extract($user)->toArray();

        $hal = Arr::dot($hal);

        $this->assertEquals('http://localhost/user/1', $hal['_links.self.href']);
        $this->assertEquals('http://localhost/address/1', $hal['_embedded.address._links.self.href']);
        $this->assertEquals('http://localhost/user/1', $hal['_embedded.address._links.user.href']);
    }

    public function testOneToOneInverseSide(): void
    {
        $address = $this->entityManager->getRepository(Entity\Address::class)
            ->find(1);
        $hal = $this->hydratorManager->extract($address)->toArray();

        $hal = Arr::dot($hal);

        $this->assertEquals('http://localhost/address/1', $hal['_links.self.href']);
        $this->assertEquals('http://localhost/user/1', $hal['_links.user.href']);
    }

    public function testManyToManyOwningSide(): void
    {
        $user = $this->entityManager->getRepository(Entity\User::class)
            ->find(1);
        $hal = $this->hydratorManager->extract($user)->toArray();

        $hal = Arr::dot($hal);

        $this->assertEquals('http://localhost/user/1', $hal['_links.self.href']);
        $this->assertEquals('http://localhost/recording?filter[users]=1', urldecode($hal['_links.recordings.href']));
        $this->assertEquals('http://localhost/address/1', $hal['_embedded.address._links.self.href']);
    }

    public function testManyToManyInverseSide(): void
    {
        $user = $this->entityManager->getRepository(Entity\Recording::class)
            ->find(1);
        $hal = $this->hydratorManager->extract($user)->toArray();

        $hal = Arr::dot($hal);

        $this->assertEquals('http://localhost/recording/1', $hal['_links.self.href']);
        $this->assertEquals('http://localhost/user?filter[recordings]=1', urldecode($hal['_links.users.href']));
    }
}
