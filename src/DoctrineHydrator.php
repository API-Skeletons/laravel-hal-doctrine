<?php

declare(strict_types=1);

namespace ApiSkeletons\Laravel\HAL\Doctrine;

use ApiSkeletons\Laravel\HAL\Hydrator;
use ApiSkeletons\Laravel\HAL\Resource;
use Doctrine\Inflector\InflectorFactory;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\MappingException;
use Exception;
use Illuminate\Foundation\Application;

use function array_diff_key;
use function array_flip;

class DoctrineHydrator extends Hydrator
{
    /** @var mixed[] */
    protected array $config                = [];
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
        $inflector = InflectorFactory::create()->build();

        $state          = [];
        $entityName     = $this->validateEntity($entity);
        $entityMetadata = $this->entityManager->getMetadataFactory()
            ->getMetadataFor($entityName);

        // Extract the entitiy
        $data = $this->entityManager->newHydrator('hal-doctrine')
            ->extract($entity);

        // Find primary key
        $identifier = $data[$entityMetadata->getIdentifier()[0]];

        // Copy fields from data to state
        foreach ($entityMetadata->getFieldNames() as $fieldName) {
            $state[$fieldName] = $data[$fieldName];
        }

        // Strip excluded fields
        $exclude = $this->config['entities'][$entityName]['exclude'] ?? [];
        if ($exclude) {
            $state = array_diff_key($state, array_flip($exclude));
        }

        // Find route name for self link
        $entityRouteName = $this->config['entities'][$entityName]['routeNames']['entity'] ??
            str_replace(
                '{entityName}',
                $inflector->urlize((new \ReflectionClass($entity))->getShortName()),
                $this->config['routeNamePatterns']['entity']
            );

        $resource = $this->hydratorManager->resource($state);
        $resource->addLink('self', route($entityRouteName, $identifier));

        return $resource;
    }

    /**
     * @return T::class
     */
    protected function validateEntity(object $entity): string
    {
        try {
            $metadata = $this->entityManager->getMetadataFactory()
                ->getMetadataFor($entity::class);

            $entityName = $metadata->getName();
        } catch (MappingException $e) {
            throw new Exception('Object ' . $entity::class . ' is not a Doctrine entity.');
        }

        // Find primary key
        if (count($metadata->getIdentifier()) > 1) {
            throw new Exception('Multi-keyed entities are not supported.');
        }

        return $entityName;
    }
}
