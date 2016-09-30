<?php

namespace test\Enum\Bundle\Doctrine\Generator;

use Enum\Bundle\Doctrine\Generator\EnumTypeGenerator;
use test\Enum\Fixtures\FooBar;
use test\Enum\Fixtures\OneTwo;

class EnumTypeGeneratorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var EnumTypeGenerator
     */
    private $sut;

    protected function setUp()
    {
        $this->sut = new EnumTypeGenerator();
    }

    public function testItReturnsClassTypeNameForEnumClass()
    {
        $this->assertEquals('__Enum__\\'.FooBar::class, $this->sut->getTypeClassName(FooBar::class));
    }

    public function testItGeneratesTypeClassContentForStringEnums()
    {
        $expectedContent = <<<EOD
namespace __Enum__\\test\Enum\Fixtures {

    final class FooBar extends \Enum\Bundle\Doctrine\EnumType
    {
        public function getName()
        {
            return 'foobar';
        }

        protected function getEnumClass()
        {
            return 'test\Enum\Fixtures\FooBar';
        }

        protected function getValueType()
        {
            return 'string';
        }
    }
}

EOD;
        $result = $this->sut->generate('foobar', FooBar::class);
        $this->assertEquals('__Enum__\\'.FooBar::class, $result->getClassName());
        $this->assertEquals($expectedContent, $result->getContent());
    }

    public function testItGeneratesTypeClassContentForIntEnums()
    {
        $expectedContent = <<<EOD
namespace __Enum__\\test\Enum\Fixtures {

    final class OneTwo extends \Enum\Bundle\Doctrine\EnumType
    {
        public function getName()
        {
            return 'onetwo';
        }

        protected function getEnumClass()
        {
            return 'test\Enum\Fixtures\OneTwo';
        }

        protected function getValueType()
        {
            return 'int';
        }
    }
}

EOD;
        $result = $this->sut->generate('onetwo', OneTwo::class);
        $this->assertEquals('__Enum__\\'.OneTwo::class, $result->getClassName());
        $this->assertEquals($expectedContent, $result->getContent());
    }
}
