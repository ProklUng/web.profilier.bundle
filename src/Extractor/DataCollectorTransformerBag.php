<?php

namespace Prokl\WebProfilierBundle\Extractor;

use LogicException;
use Symfony\Component\DependencyInjection\ServiceLocator;

/**
 * Class DataCollectorTransformerBag
 * @since 16.08.2021
 */
class DataCollectorTransformerBag
{
    /**
     * @var ServiceLocator $transformers Трансформеры.
     */
    private $transformers;

    /**
     * @param ServiceLocator $transformers Трансформеры.
     */
    public function __construct(ServiceLocator $transformers)
    {
        $this->transformers = $transformers;
    }

    /**
     * @return ServiceLocator
     */
    public function all() : ServiceLocator
    {
        return $this->transformers;
    }

    /**
     * @param string $collectorId Класс коллектора.
     *
     * @return string
     * @throws LogicException Когда коллектор не существует.
     */
    public function getTemplate(string $collectorId) : string
    {
        if (!$this->transformers->has($collectorId)) {
            throw new LogicException(
                sprintf('DataCollector with ID %s not exist', $collectorId)
            );
        }

        return '';
    }
}
