<?php

namespace Prokl\WebProfilierBundle\Transformers;

use ReflectionException;
use Symfony\Component\HttpKernel\DataCollector\DataCollector;
use Symfony\Component\HttpKernel\DataCollector\RequestDataCollector;
use Symfony\Component\VarDumper\Cloner\Data;
use Prokl\WebProfilierBundle\Contract\DataCollectorTransformerInterface;
use Symfony\Component\VarDumper\Cloner\VarCloner;

/**
 * Class RequestDataJsoner
 * @package Prokl\WebProfilierBundle\Transformers
 *
 * @since 16.08.2021
 */
class RequestDataDataCollectorTransformer implements DataCollectorTransformerInterface
{
    /**
     * @var $cloner
     */
    private $cloner;

    /**
     * @inheritDoc
     * @param RequestDataCollector $dataCollector Data collector.
     * @throws ReflectionException
     */
    public function transform($dataCollector) : array
    {
        $attr = $dataCollector->getRequestAttributes();
        $controller = $attr->get('_controller', '');
        $data = $controller ? $this->parseController($controller->getValue()) : 'n/a';

        return [
            'collector' => $dataCollector,
            'phpself' => $_SERVER['PHP_SELF'],
            'controller_data' => $this->cloneVar($data),
            'raw_session' => $this->cloneVar($_SESSION)
        ];
    }

    /**
     * @inheritDoc
     */
    public function getTemplate() : string
    {
        return '/collectors/request.html.twig';
    }

    /**
     * @inheritDoc
     */
    public static function support(DataCollector $dataCollector) : bool
    {
        return is_a($dataCollector, RequestDataCollector::class);
    }

    /**
     * Parse a controller.
     *
     * @param string|object|array|null $controller The controller to parse.
     *
     * @return array|string An array of controller data or a simple string
     * @throws ReflectionException
     */
    private function parseController($controller)
    {
        if (\is_string($controller) && str_contains($controller, '::')) {
            $controller = explode('::', $controller);
        }

        if (\is_array($controller)) {
            try {
                $r = new \ReflectionMethod($controller[0], $controller[1]);

                return [
                    'class' => \is_object($controller[0]) ? get_debug_type($controller[0]) : $controller[0],
                    'method' => $controller[1],
                    'file' => $r->getFileName(),
                    'line' => $r->getStartLine(),
                ];
            } catch (ReflectionException $e) {
                if (\is_callable($controller)) {
                    // using __call or  __callStatic
                    return [
                        'class' => \is_object($controller[0]) ? get_debug_type($controller[0]) : $controller[0],
                        'method' => $controller[1],
                        'file' => 'n/a',
                        'line' => 'n/a',
                    ];
                }
            }
        }

        if ($controller instanceof \Closure) {
            $r = new \ReflectionFunction($controller);

            $controller = [
                'class' => $r->getName(),
                'method' => null,
                'file' => $r->getFileName(),
                'line' => $r->getStartLine(),
            ];

            if (str_contains($r->name, '{closure}')) {
                return $controller;
            }
            $controller['method'] = $r->name;

            if ($class = $r->getClosureScopeClass()) {
                $controller['class'] = $class->name;
            } else {
                return $r->name;
            }

            return $controller;
        }

        if (\is_object($controller)) {
            $r = new \ReflectionClass($controller);

            return [
                'class' => $r->getName(),
                'method' => null,
                'file' => $r->getFileName(),
                'line' => $r->getStartLine(),
            ];
        }

        return \is_string($controller) ? $controller : 'n/a';
    }

    /**
     * Converts the variable into a serializable Data instance.
     *
     * This array can be displayed in the template using
     * the VarDumper component.
     *
     * @param mixed $var
     *
     * @return Data
     */
    protected function cloneVar($var)
    {
        if ($var instanceof Data) {
            return $var;
        }
        if (null === $this->cloner) {
            $this->cloner = new VarCloner();
            $this->cloner->setMaxItems(-1);
        }

        return $this->cloner->cloneVar($var);
    }
}
