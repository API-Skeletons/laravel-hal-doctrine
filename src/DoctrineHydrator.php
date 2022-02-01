<?php

declare(strict_types=1);

namespace ApiSkeletons\Laravel\HAL\Doctrine;

use ApiSkeletons\Laravel\HAL\Hydrator;
use ApiSkeletons\Laravel\HAL\Resource;
use DateTime;
use Doctrine\Inflector\Inflector;
use Doctrine\Inflector\InflectorFactory;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\MappingException;
use Exception;
use Illuminate\Foundation\Application;
use ReflectionClass;

use function array_diff_key;
use function array_flip;
use function count;
use function in_array;
use function print_r;
use function route;
use function str_replace;

class DoctrineHydrator extends Hydrator
{
    /** @var mixed[] */
    protected array $config                = [];
    protected string $configurationSection = 'default';
    protected EntityManager $entityManager;
    protected Inflector $inflector;

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
        $this->inflector     = InflectorFactory::create()->build();
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

        // Find identifier
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

        // Convert datetime objects to ISO 8601
        foreach ($state as $key => $value) {
            if (! ($value instanceof DateTime)) {
                continue;
            }

            $state[$key] = $value->format('c');
        }

        // Build resource and set state
        $resource = $this->hydratorManager->resource($state);

        // Find route and add self link
        $entityRouteName = $this->getRouteName($entityName, 'entity');
        $resource->addLink('self', route($entityRouteName, $identifier));

        // Find collection valued relationships and add links
        foreach ($entityMetadata->getAssociationNames() as $associationName) {
            // Ignore excluded fields
            if (in_array($associationName, $exclude)) {
                continue;
            }

            if ($entityMetadata->isCollectionValuedAssociation($associationName)) {
                $associationMapping   = $entityMetadata->getAssociationMapping($associationName);
                $associationRouteName = $this->getRouteName($associationMapping['targetEntity'], 'collection');
                $resource->addLink(
                    $this->inflector->urlize($associationName),
                    route($associationRouteName, [
                        'filter' => [$associationMapping['mappedBy'] => $identifier],
                    ])
                );
            } else {
                // For 1:1 relationships, only embed the owning side
                // For the inverse side, include a link
                if ($entityMetadata->isAssociationInverseSide($associationName)) {
                    $associationMapping = $entityMetadata->getAssociationMapping($associationName);

                    print_r($associationMapping);
                    die('1:1 inverse side not finished');

                    $associationRouteName = $this->getRouteName($associationMapping['targetEntity'], 'collection');
                    $resource->addLink(
                        $this->inflector->urlize($associationName),
                        route($associationRouteName, [
                            'filter' => [$associationMapping['mappedBy'] => $identifier],
                        ])
                    );
                } else {
                    $resource->addEmbeddedResource(
                        $this->inflector->urlize($associationName),
                        $data[$associationName]
                    );
                }
            }
        }

        return $resource;
    }

    protected function getRouteName(string $entityName, string $routeType): string
    {
        return $this->config['entities'][$entityName]['routeNames'][$routeType] ??
            str_replace(
                '{entityName}',
                $this->inflector->urlize((new ReflectionClass($entityName))->getShortName()),
                $this->config['routeNamePatterns'][$routeType]
            );
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
