<?php

namespace test\phpchain;

class ChainStepTest extends \PHPUnit_Framework_TestCase
{
    public function testBreakTheChainWithReturnValue()
    {
        $stepOne = $this->getMockForAbstractClass('phpchain\ChainStep');
        $stepTwo = $this->getMockForAbstractClass('phpchain\ChainStep');

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
        $step = $this->getMockForAbstractClass('phpchain\ChainStep', [], '', true, true, true, ['should']);
        $step->expects($this->once())
            ->method('should')
            ->willReturn(false);
        $step->expects($this->never())
            ->method('process');

        $this->assertSame(null, $step->execute(new \ArrayObject));
    }
}
