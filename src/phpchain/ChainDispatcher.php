<?php

namespace phpchain;

use Interop\Container\ContainerInterface;

class ChainDispatcher
{
    /** @var ContainerInterface */
    private $container;

    private $definitions = [];

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function define($code, array $steps)
    {
        $this->definitions[$code] = $steps;

        return $this;
    }

    /**
     * @param string $code
     *
     * @return ChainStep
     */
    public function dispatch($code)
    {
        if (empty($this->definitions[$code])) {
            throw new \OutOfBoundsException('No chain with code ' . $code);
        }

        $chainDefinition = $this->definitions[$code];
        $currentStep = $firstStep = null;

        while ($step = array_shift($chainDefinition)) {
            $stepInstance = $step instanceof ChainStep
                ? $step
                : $this->container->get($step);

            if (is_null($currentStep)) {
                $firstStep = $stepInstance;
            } else {
                $currentStep->setNext($stepInstance);
            }

            $currentStep = $stepInstance;
        }

        return $firstStep;
    }
}
