<?php

namespace test\Enum\Bundle\Doctrine;

use Doctrine\DBAL\Types\Type;
use Enum\Bundle\Doctrine\EnumTypeRegistry;
use Enum\Bundle\Doctrine\EnumTypeStorage;
use Enum\Bundle\Doctrine\Generator\EnumTypeGenerator;
use Enum\Bundle\Doctrine\Generator\GenerationResult;
use Enum\EnumException;
use PHPUnit\Framework\TestCase;
use test\Enum\Fixtures\FooBar;
use test\Enum\Fixtures\FooBarType;
use test\Enum\Fixtures\NotEnum;

class EnumTypeRegistryTest extends TestCase
{
    /**
     * @var EnumTypeRegistry
     */
    private $sut;

    /**
     * @var EnumTypeGenerator
     */
    private $generator;

    /**
     * @var EnumTypeStorage
     */
    private $storage;

    protected function setUp()
    {
        $this->generator = \Mockery::mock(EnumTypeGenerator::class);
        $this->storage = \Mockery::mock(EnumTypeStorage::class);
        $this->sut = new EnumTypeRegistry($this->generator, $this->storage);

        if (Type::hasType('foobar')) {
            Type::overrideType('foobar', null);
        }
    }

    public function testItRegistersAutoloader()
    {
        $autoloadersCount = count(spl_autoload_functions());
        $registry = new EnumTypeRegistry($this->generator, $this->storage);
        $registry->registerAutoloader();

        $this->assertEquals($autoloadersCount + 1, count(spl_autoload_functions()));
    }

    public function testItThrowsExceptionWhenAddingTypeWithInvalidName()
    {
        $this->expectException('Enum type name contains invalid characters. Only letters, numbers and underscores are allowed.');

        $this->sut->addType('foobar,1234.!@#$', FooBar::class);
    }

//    public function testItThrowsExceptionWhenAddingTypeToClassThatIsNotAnEnum()
//    {
//        $this->expectExceptionObject(EnumException::notValidEnumClass(NotEnum::class));
//
//        $this->sut->addType('yolo', NotEnum::class);
//    }
//
//    public function testItAddsTypeThatIsAlreadyInStorage()
//    {
//        $this->generator
//            ->shouldReceive('getTypeClassName')
//            ->with(FooBar::class)
//            ->andReturn('__Enum__\\'.FooBarType::class);
//
//        $this->storage
//            ->shouldReceive('exists')
//            ->with('__Enum__\\'.FooBarType::class)
//            ->andReturn(true);
//
//        $this->generator->shouldNotReceive('generate');
//        $this->storage->shouldNotReceive('save');
//
//        $this->assertFalse(Type::hasType('foobar'));
//        $this->sut->addType('foobar', FooBar::class);
//
//        $map = Type::getTypesMap();
//        $this->assertEquals('__Enum__\\'.FooBarType::class, $map['foobar']);
//    }
//
//    public function testItAddsTypeThatIsNotYetInStorage()
//    {
//        $this->generator
//            ->shouldReceive('getTypeClassName')
//            ->with(FooBar::class)
//            ->andReturn('__Enum__\\'.FooBarType::class);
//
//        $this->storage
//            ->shouldReceive('exists')
//            ->with('__Enum__\\'.FooBarType::class)
//            ->andReturn(false);
//
//        $this->generator
//            ->shouldNotReceive('generate')
//            ->with('foobar', FooBar::class)
//            ->andReturn(new GenerationResult('__Enum__\\'.FooBarType::class, 'type_class_content'));
//
//        $this->storage
//            ->shouldReceive('save')
//            ->with('__Enum__\\'.FooBarType::class, 'type_class_content');
//
//        $this->assertFalse(Type::hasType('foobar'));
//        $this->sut->addType('foobar', FooBar::class);
//
//        $map = Type::getTypesMap();
//        $this->assertEquals('__Enum__\\'.FooBarType::class, $map['foobar']);
//    }
//
//    public function testItReturnsTypeExistance()
//    {
//        $this->assertFalse($this->sut->hasType('foobar'));
//        Type::addType('foobar', FooBarType::class);
//        $this->assertTrue($this->sut->hasType('foobar'));
//    }
}
