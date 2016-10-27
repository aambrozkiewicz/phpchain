<?php

namespace test\phpchain;

use Interop\Container\ContainerInterface;
use phpchain\ChainDispatcher;
use phpchain\ChainStep;

class ChainDispatcherTest extends \PHPUnit_Framework_TestCase
{
    public function testDispatchSingleStepChain()
    {
        $stepOne = $this->getMockForAbstractClass(ChainStep::class);

        $container = $this->createMock(ContainerInterface::class);
        $container
            ->expects($this->once())
            ->method('get')
            ->with('stepOne')
            ->willReturn($stepOne);

        $dispatcher = new ChainDispatcher($container);
        $dispatcher->define('hello.world', [
            'stepOne'
        ]);

        $this->assertSame($stepOne, $dispatcher->dispatch('hello.world'));
    }

    public function testDispatchChainWithMultipleSteps()
    {
        $stepOne = $this->getMockForAbstractClass(ChainStep::class);
        $stepTwo = $this->getMockForAbstractClass(ChainStep::class);

        $stepTwo->expects($this->once())
            ->method('process')
            ->will($this->returnCallback(function(\ArrayObject $input) {
                $input['stepAlteredKey'] = true;
            }));

        $container = $this->createMock(ContainerInterface::class);
        $container
            ->expects($this->at(0))
            ->method('get')
            ->with('stepOne')
            ->willReturn($stepOne);
        $container
            ->expects($this->at(1))
            ->method('get')
            ->with('stepTwo')
            ->willReturn($stepTwo);

        $dispatcher = new ChainDispatcher($container);
        $dispatcher->define('user.login', [
            'stepOne', 'stepTwo'
        ]);

        $this->assertSame($stepOne, $dispatcher->dispatch('user.login'));

        $input = new \ArrayObject([
            'hello' => 'world'
        ]);

        $this->assertSame(null, $stepOne->execute($input));
        $this->assertArrayHasKey('stepAlteredKey', $input);
    }
}
