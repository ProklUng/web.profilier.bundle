<?php

namespace Prokl\WebProfilierBundle\DataCollector\Decorators;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\DataCollector\DataCollector;

/**
 * Class DataCollectorDecorator
 *
 * @since 24.08.2021
 */
class DataCollectorDecorator extends DataCollector
{
    /**
     * @var DataCollector $dataCollector Декорируемый data collector.
     */
    private $dataCollector;

    /**
     * @param DataCollector $dataCollector Декорируемый data collector.
     */
    public function __construct(DataCollector $dataCollector)
    {
        $this->dataCollector = $dataCollector;
    }

    /**
     * Класс декорируемого data collector.
     *
     * @return string
     */
    public function getClass() : string
    {
        return get_class($this->dataCollector);
    }

    /**
     * Оригинальный data collector.
     *
     * @return DataCollector
     */
    public function getDataCollector(): DataCollector
    {
        return $this->dataCollector;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->dataCollector->getName();
    }

    public function lateCollect()
    {
        return $this->dataCollector->lateCollect();
    }

    public function reset()
    {
        return $this->dataCollector->reset();
    }

    /**
     * {@inheritdoc}
     */
    public function collect(Request $request, Response $response, \Throwable $exception = null)
    {
        $result = $this->dataCollector->collect($request, $response, $exception);

        // Внутренние нужды. Для сообщения между разными модулями в Битриксе
        // запускается кастомное событие OnAfterDataCollectorDone
        if (defined('B_PROLOG_INCLUDED') && B_PROLOG_INCLUDED===true) {
            $events = GetModuleEvents('', 'OnAfterDataCollectorDone', true);
            foreach ($events as $event) {
                ExecuteModuleEventEx($event, ['dataCollector' => $this]);
            }
        }

        return $result;
    }
}