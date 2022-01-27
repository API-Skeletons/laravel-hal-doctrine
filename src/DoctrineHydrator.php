<?php

namespace ApiSkeletons\Laravel\Doctrine\HAL;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\MappingException;
use Illuminate\Foundation\Application;

class DoctrineHydrator
{
    private Application $application;
    private string $configurationSection = 'default';
    private array $loadedConfiguration = [];
    private EntityManager $entityManager;

    public function __construct(Application $application)
    {
        $this->application = $application;
    }

    /**
     * @throws \Exception
     */
    public function config(string $section): self
    {
        if ($this->loadedConfiguration) {
            throw new \Exception('Cannot set configuration section.  Configuration is already loaded.');
        }
        $this->configurationSection = $section;

        return $this;
    }

    public function extract(object $entity): array
    {
        $this->validateEntity($entity);

        return [];
    }

    protected function validateEntity(object $entity): self
    {
        if (! isset($this->configuration()['entityManager'])) {
            throw new \Exception('Entity Manager configuration is missing');
        }

        $this->entityManager = $this->application
            ->get($this->configuration()['entityManager']);

        try {
            $entityName = $this->em->getMetadataFactory()
                ->getMetadataFor(get_class($entity))->getName();
        } catch (MappingException $e) {
            throw new \Exception('Object ' . get_class($entity) . ' is not a Doctrine entity.');
        }

        if (! $this->hasConfig($entityName)) {
            throw new \Exception('Entity is not mapped in the configuration');
        }

        return $this;
    }

    private function configuration(): array
    {
        if (! $this->loadedConfiguration) {
            $this->loadedConfiguration =
                config('doctrine-hal.' . $this->configurationSection);
        }

        return $this->loadedConfiguration;
    }

    private function entityConfiguration(): array
    {
        if (! isset($this->configuration()['entities'])) {
            throw new \Exception('entities section is missing from configuration');
        }

        return $this->configuration()['entities'];
    }

    private function hasConfig(string $entityName): bool
    {
        return isset($this->entityConfiguration()[$entityName]);
    }

    protected function extractEntity(object $entity)
    {

    }
}
