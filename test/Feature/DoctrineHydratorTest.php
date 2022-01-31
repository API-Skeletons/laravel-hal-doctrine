<?php

namespace ApiSkeletonsTest\Laravel\Hal\Doctrine\Feature;

use ApiSkeletonsTest\Laravel\HAL\Doctrine\TestCase;
use ApiSkeletonsTest\Laravel\HAL\Doctrine\Entity;
use Doctrine\ORM\EntityManager;
use DateTime;

final class DoctrineHydratorTest extends TestCase
{
    private EntityManager $entityManager;

    public function setUp(): void
    {
        parent::setUp();
        $this->entityManager = $this->createDatabase(app('em'));
    }

    public function testSuccess(): void
    {
        $this->assertTrue(true);
    }

    public function testCreateDatabase(): void
    {
        $this->assertInstanceOf(EntityManager::class, $this->entityManager);
    }

    public function testPopulateData(): void
    {
        $this->populateData();

        $artist = $this->entityManager->getRepository(Entity\Artist::class)
            ->find(1);

        $this->assertEquals('Grateful Dead', $artist->getName());
    }

    protected function populateData(): void
    {
        $artists = [
            'Grateful Dead' => [
                '1995-02-21' => [
                    'venue' => 'Delta Center',
                    'city' => 'Salt Lake City',
                    'state' => 'Utah',
                    'recordings' => [
                        'SBD> D> CD-R> EAC> SHN; via Jay Serafin, Brian '
                        . 'Walker; see info file and pub comments for notes; '
                        . 'possibly "click track" audible on a couple tracks',
                        'DSBD > 1C > DAT; Seeded to etree by Dan Stephens',
                    ]
                ],
                '1969-11-08' => [
                    'venue' => 'Fillmore Auditorium',
                    'city' => 'San Francisco',
                    'state' => 'California',
                ],
                '1977-05-08' => [
                    'venue' => 'Barton Hall, Cornell University',
                    'city' => 'Ithaca',
                    'state' => 'New York',
                ],
                '1995-07-09' => [
                    'venue' => 'Soldier Field',
                    'city' => 'Chicago',
                    'state' => 'Illinois',
                ],
            ],
            'Phish' => [
                '1998-11-02' => [
                    'venue' => 'E Center',
                    'city' => 'West Valley City',
                    'state' => 'Utah',
                    'recordings' => [
                        'AKG480 > Aerco preamp > SBM-1',
                    ],
                ],
                '1999-12-31' => [
                    'venue' => null,
                    'city' => 'Big Cypress',
                    'state' => 'Florida',
                ],
            ],
            'String Cheese Incident' => [
                '2002-06-21' => [
                    'venue' => 'Bonnaroo',
                    'city' => 'Manchester',
                    'state' => 'Tennessee',
                ],
            ],
        ];
        foreach ($artists as $name => $performances) {
            $artist = (new Entity\Artist())
                ->setName($name);
            $this->entityManager->persist($artist);

            foreach ($performances as $performanceDate => $location) {
                $performance = (new Entity\Performance())
                    ->setPerformanceDate(DateTime::createFromFormat('Y-m-d H:i:s', $performanceDate . ' 00:00:00'))
                    ->setVenue($location['venue'])
                    ->setCity($location['city'])
                    ->setState($location['state'])
                    ->setArtist($artist);
                $this->entityManager->persist($performance);

                if (isset($location['recordings'])) {
                    foreach ($location['recordings'] as $source) {
                        $recording = (new Entity\Recording())
                            ->setSource($source)
                            ->setPerformance($performance);
                        $this->entityManager->persist($recording);
                    }

                }
            }
        }

        $this->entityManager->flush();
    }
}
