<?php

namespace Prokl\WebProfilierBundle\Extractor;

use Prokl\WebProfilierBundle\Contract\DataCollectorTransformerInterface;
use Prokl\WebProfilierBundle\Utils\ExternalDataCollectorsBag;
use RuntimeException;
use Symfony\Component\HttpKernel\DataCollector\LateDataCollectorInterface;
use Symfony\Component\HttpKernel\Profiler\Profile;
use Symfony\Component\HttpKernel\Profiler\Profiler;

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
     * @var Profiler $profiler Profiler.
     */
    private $profiler;

    /**
     * @param DataCollectorTransformerBag $transformers Jsoners bag.
     * @param Profiler                    $profiler     Profiler.
     */
    public function __construct(
        Profiler $profiler,
        DataCollectorTransformerBag $transformers
    ) {
        $this->transformers = $transformers;
        $this->profiler = $profiler;
    }

    /**
     * @param string $token Токен.
     *
     * @return array
     */
    public function extractByToken(string $token) : array
    {
        $profile = $this->profiler->loadProfile($token);
        if (!$profile) {
            throw new RuntimeException('Profile by token ' . $token . ' not found.');
        }

        $data[$profile->getUrl()] = $this->extract($profile);

        return $data;
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

        // Суть манипуляций: зацепить внешние дата-коллекторы, полученные через событие
        // и пустить их в дело.
        $externalCollectorsBag = new ExternalDataCollectorsBag();
        $externalCollectors = $externalCollectorsBag->all();

        foreach ($externalCollectors as $externalCollector) {
            $collectorForAdd = $externalCollector;
            // Декоратор
            if (method_exists($externalCollector, 'getDataCollector')) {
                $collectorForAdd = $externalCollector->getDataCollector();
            }
            $profile->addCollector($collectorForAdd);
        }

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
            $result[$name]['date'] = date('Y-m-d H:i:s', $profile->getTime());
        }

        return $result;
    }

    /**
     * Свежайший токен.
     *
     * @return string|null
     */
    public function latestToken() : ?string
    {
        if ($latest = current($this->profiler->find(null, null, 1, null, null, null))) {
            return $latest['token'];
        }

        return null;
    }
}