<?php

namespace Prokl\WebProfilierBundle\Extractor;

use Prokl\WebProfilierBundle\Contract\DataCollectorTransformerInterface;
use Symfony\Component\HttpKernel\DataCollector\LateDataCollectorInterface;
use Symfony\Component\HttpKernel\Profiler\Profile;

/**
 * Class ProfileExtractor
 * @package Prokl\WebProfilierBundle\Extractor
 *
 * @since 16.08.2021
 */
class ProfileExtractor
{
    /**
     * @var DataCollectorTransformerBag $transformers Jsoners bag.
     */
    private $transformers;

    /**
     * @param DataCollectorTransformerBag $transformers Jsoners bag.
     */
    public function __construct(DataCollectorTransformerBag $transformers)
    {
        $this->transformers = $transformers;
    }

    /**
     * @param Profile $profile Profile.
     *
     * @return array
     */
    public function extract(Profile $profile) : array
    {
        $transformers = $this->transformers->all();

        $result = [];
        foreach ($profile->getCollectors() as $collector) {
            $name = $collector->getName();
            if (!$transformers->has($name)) {
                continue;
            }

            // Из потрохов Symfony
            if ($collector instanceof LateDataCollectorInterface) {
                $collector->lateCollect();
            }

            $handler = $transformers->get($name);

            if (!$handler::support($collector)) {
                continue;
            }

            /** @var DataCollectorTransformerInterface $jsonerHandler */
            $result[$name] = $handler->transform($collector);

            $template = $handler->getTemplate();
            $result[$name]['template'] = $template;
            $result[$name]['id'] = str_replace('.', '_', $name);
        }

        return $result;
    }
}