<?php

namespace test\phpchain;

use phpchain\ChainStep;

class ChainStepTest extends \PHPUnit_Framework_TestCase
{
    public function testBreakTheChainWithReturnValue()
    {
        $stepOne = $this->getMockForAbstractClass(ChainStep::class);
        $stepTwo = $this->getMockForAbstractClass(ChainStep::class);

        $stepOne->expects($this->once())
            ->method('process')
            ->will($this->returnCallback(function() {
                return 1;
            }));

        $stepOne->setNext($stepTwo);

        $this->assertNotSame('value', $result = $stepOne->execute(new \ArrayObject));
        $this->assertSame(1, $result);
    }

    public function testExecuteWhenAppropriate()
    {
        $step = $this->getMockForAbstractClass(ChainStep::class, [], '', true, true, true, ['should']);
        $step->expects($this->once())
            ->method('should')
            ->willReturn(false);
        $step->expects($this->never())
            ->method('process');

        $this->assertSame(null, $step->execute(new \ArrayObject));
    }
}
