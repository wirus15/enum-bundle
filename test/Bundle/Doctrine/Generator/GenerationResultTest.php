<?php

namespace Enum\Bundle\Doctrine\Generator;

class GenerationResultTest extends \PHPUnit_Framework_TestCase
{
    public function testItReturnsClassNameAndContent()
    {
        $sut = new GenerationResult('foo', 'bar');
        $this->assertEquals('foo', $sut->getClassName());
        $this->assertEquals('bar', $sut->getContent());
    }
}
