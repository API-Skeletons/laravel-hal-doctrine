<?php

declare(strict_types=1);

namespace ApiSkeletons\Laravel\HAL\Doctrine;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\MappingException;
use Exception;
use Illuminate\Foundation\Application;

class DoctrineHydrator
{
    protected string $configurationSection = 'default';
    protected array $config                = [];
    protected EntityManager $entityManager;

    public function __construct(Application $application)
    {
        $this->config = $application->get('config')['hal-doctrine'];

        if (! isset($this->config['entityManager'])) {
            throw new Exception(
                'Entity Manager configuration is missing for ' . $this->configurationSection
            );
        }

        $this->entityManager = $application->get($this->config['entityManager']);
    }

    public function extract(object $entity): array
    {
        $entityName     = $this->validateEntity($entity);
        $entityMetadata = $this->em->getMetadataFactory()
            ->getMetadataFor($entityName);

        return [];
    }

    protected function validateEntity(object $entity): string
    {
        try {
            $entityName = $this->em->getMetadataFactory()
                ->getMetadataFor($entity::class)->getName();
        } catch (MappingException $e) {
            throw new Exception('Object ' . $entity::class . ' is not a Doctrine entity.');
        }

        if (! $this->hasConfig($entityName)) {
            throw new Exception('Entity is not mapped in the configuration');
        }

        return $entityName;
    }
}
