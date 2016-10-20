<?php

namespace phpchain;

/**
 * Every step you take must inherit from this
 *
 * @package phpchain
 */
abstract class ChainStep
{
    protected $nextStep;

    /**
     * @param ChainStep $nextStep
     *
     * @return ChainStep Next step passed to this method ealier
     */
    public function setNext(ChainStep $nextStep)
    {
        $this->nextStep = $nextStep;

        return $nextStep;
    }

    /**
     * Executes a chain of steps.
     *
     * @param \ArrayAccess $input
     *
     * @return mixed On failure, next step object is returned (or null)
     */
    public function execute(\ArrayAccess $input)
    {
        $result = $this->process($input);

        return $result !== null || is_null($this->nextStep)
            ? $result
            : $this->nextStep->execute($input);
    }

    /**
     * This should do the step logic.
     *
     * @param \ArrayAccess $input
     *
     * @return boolean Indicate failure by returning false, which will stop the chain
     */
    abstract protected function process(\ArrayAccess $input);
}
