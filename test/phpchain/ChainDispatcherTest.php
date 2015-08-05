<?php

namespace test\phpchain;

use phpchain\ChainDispatcher;

class ChainDispatcherTest extends \PHPUnit_Framework_TestCase
{
    public function testDispatchSingleStepChain()
    {
        $stepOne = $this->getMockForAbstractClass('phpchain\ChainStep');

        $container = new \ArrayObject([
            'stepOne' => $stepOne,
            'otherDependency' => 'no definition'
        ]);

        $dispatcher = new ChainDispatcher($container);
        $dispatcher->chain('user.register')->define([
            'stepOne'
        ]);

        $this->assertSame($stepOne, $dispatcher->dispatch());
    }

    public function testDispatchChainWithMultipleSteps()
    {
        $stepOne = $this->getMockForAbstractClass('phpchain\ChainStep');
        $stepTwo = $this->getMockForAbstractClass('phpchain\ChainStep');

        $stepTwo->expects($this->once())
            ->method('process')
            ->will($this->returnCallback(function(\ArrayObject $input) {
                $input['stepAlteredKey'] = true;
            }));

        $container = new \ArrayObject([
            'stepOne' => $stepOne,
            'stepTwo' => $stepTwo
        ]);

        $dispatcher = new ChainDispatcher($container);
        $dispatcher->chain('user.login')->define([
            'stepOne', 'stepTwo'
        ]);

        $this->assertSame($stepOne, $dispatcher->dispatch());

        $input = new \ArrayObject([
            'hello' => 'world'
        ]);

        $this->assertSame(null, $stepOne->execute($input));
        $this->assertArrayHasKey('stepAlteredKey', $input);
    }
}
