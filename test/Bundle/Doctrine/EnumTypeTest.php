<?php

namespace test\Enum\Bundle\Doctrine;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use PHPUnit\Framework\TestCase;
use test\Enum\Fixtures\FooBar;
use test\Enum\Fixtures\FooBarType;
use test\Enum\Fixtures\OneTwo;
use test\Enum\Fixtures\OneTwoType;

class EnumTypeTest extends TestCase
{
    protected function setUp()
    {
        if (Type::hasType('foobar') === false) {
            Type::addType('foobar', FooBarType::class);
        }

        if (Type::hasType('onetwo') === false) {
            Type::addType('onetwo', OneTwoType::class);
        }
    }

    public function testItReturnsVarcharSqlDeclarationForEnumsWithStringValues()
    {
        $sut = Type::getType('foobar');
        $platform = \Mockery::mock(AbstractPlatform::class);
        $platform
            ->shouldReceive('getVarcharTypeDeclarationSQL')
            ->andReturn('varchar[255]');

        $this->assertEquals('varchar[255]', $sut->getSQLDeclaration([], $platform));
    }

    public function testItReturnsIntegerSqlDeclarationForEnumsWithIntValues()
    {
        $sut = Type::getType('onetwo');
        $platform = \Mockery::mock(AbstractPlatform::class);
        $platform
            ->shouldReceive('getIntegerTypeDeclarationSQL')
            ->andReturn('int[11]');

        $this->assertEquals('int[11]', $sut->getSQLDeclaration([], $platform));
    }

    public function testItConvertsEnumsToDatabaseValue()
    {
        $sut = Type::getType('foobar');
        $platform = \Mockery::mock(AbstractPlatform::class);
        $foo = FooBar::get(FooBar::FOO);

        $this->assertEquals('foo', $sut->convertToDatabaseValue($foo, $platform));
        $this->assertEquals('something_else', $sut->convertToDatabaseValue('something_else', $platform));
    }

    public function testItConvertsValuesToEnums()
    {
        $foobarType = Type::getType('foobar');
        $onetwoType = Type::getType('onetwo');
        $platform = \Mockery::mock(AbstractPlatform::class);

        $this->assertEquals(FooBar::get(FooBar::BAR), $foobarType->convertToPHPValue(FooBar::BAR, $platform));
        $this->assertEquals(OneTwo::get(OneTwo::THREE), $onetwoType->convertToPHPValue(OneTwo::THREE, $platform));
    }
}
