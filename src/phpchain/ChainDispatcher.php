<?php

namespace phpchain;

class ChainDispatcher
{
    private $code;
    private $container;
    private $definitions;

    /**
     * @param \ArrayAccess $container Dependency Injection Container
     */
    public function __construct(\ArrayAccess $container)
    {
        $this->container = $container;
    }

    public function chain($code)
    {
        $this->code = $code;

        return $this;
    }

    public function define(array $steps)
    {
        $this->definitions[$this->code] = $steps;

        return $this;
    }

    public function dispatch()
    {
        if (empty($this->definitions[$this->code])) {
            throw new \OutOfBoundsException('No chain with code ' . $this->code);
        }

        $chainDefinition = $this->definitions[$this->code];
        $firstStepDefinition = array_shift($chainDefinition);
        $firstStep = $currentStep = is_object($firstStepDefinition)
            ? $firstStepDefinition
            : $this->container[$firstStepDefinition];

        foreach ($chainDefinition as $step) {
            $stepInstance = is_object($step) ? $step : $this->container[$step];
            $currentStep = $currentStep->setNext($stepInstance);
        }

        $this->code = null;

        return $firstStep;
    }
}
