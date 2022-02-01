<?php

declare(strict_types=1);

namespace ApiSkeletons\Laravel\HAL\Doctrine;

use ApiSkeletons\Laravel\HAL\Hydrator;
use ApiSkeletons\Laravel\HAL\Resource;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\MappingException;
use Exception;
use Illuminate\Foundation\Application;

use function array_diff_key;
use function array_flip;

class DoctrineHydrator extends Hydrator
{
    /** @var mixed[] */
    protected array $config = [];
    protected string $configurationSection = 'default';
    protected EntityManager $entityManager;

    public function __construct(Application $application)
    {
        if (! $application->get('config')['hal-doctrine']) {
            throw new Exception('hal-doctrine config is missing');
        }

        $this->config = $application->get('config')['hal-doctrine'][$this->configurationSection];

        if (! isset($this->config['entityManager'])) {
            throw new Exception(
                'Entity Manager configuration is missing for ' . $this->configurationSection
            );
        }

        $this->entityManager = $application->get($this->config['entityManager']);
    }

    public function extract(mixed $entity): Resource
    {
        $state          = [];
        $entityName     = $this->validateEntity($entity);
        $entityMetadata = $this->entityManager->getMetadataFactory()
            ->getMetadataFor($entityName);

        // Extract the entitiy
        $data = $this->entityManager->newHydrator('hal-doctrine')
            ->extract($entity);

        // Copy fields from data to state
        foreach ($entityMetadata->getFieldNames() as $fieldName) {
            $state[$fieldName] = $data[$fieldName];
        }

        // Strip excluded fields
        /**
         * psalm:ignore MixedArrayAccess
         */
        $exclude = $this->config['entities'][$entityName]['exclude'] ?? [];
        if ($exclude) {
            $state = array_diff_key($state, array_flip($exclude));
        }

        return $this->hydratorManager->resource($state);
    }

    /**
     * @return T::class
     */
    protected function validateEntity(object $entity): string
    {
        try {
            $entityName = $this->entityManager->getMetadataFactory()
                ->getMetadataFor($entity::class)->getName();
        } catch (MappingException $e) {
            throw new Exception('Object ' . $entity::class . ' is not a Doctrine entity.');
        }

        return $entityName;
    }
}
