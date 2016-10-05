<?php

namespace test\Enum\Bundle\Doctrine\TypeStorage;

use Enum\Bundle\Doctrine\TypeStorage\MemoryEnumTypeStorage;
use test\Enum\Fixtures\FooBarType;
use test\Enum\Fixtures\OneTwoType;

class MemoryEnumTypeStorageTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var MemoryEnumTypeStorage
     */
    private $sut;

    protected function setUp()
    {
        $this->sut = new MemoryEnumTypeStorage();
    }

    public function testItSavesTypes()
    {
        $this->assertFalse($this->sut->exists(FooBarType::class));

        $this->sut->save(FooBarType::class, 'foo_bar_type_content');
        $this->assertTrue($this->sut->exists(FooBarType::class));
    }

    public function testItReturnsTrueIfClassIsAlreadyLoaded()
    {
        $this->assertTrue(class_exists(FooBarType::class, true));
        $this->assertTrue($this->sut->load(FooBarType::class));
    }

    public function testItReturnsFalseIfClassDoesNotExistInStorage()
    {
        $this->assertFalse(class_exists('NonExistingClass', false));
        $this->assertFalse($this->sut->load('NonExistingClass'));
    }

    public function testItLoadsAndEvaluatesClass()
    {
        $this->sut->save('TotallyNewClass', 'final class TotallyNewClass {}');
        $this->assertTrue($this->sut->load('TotallyNewClass'));
        $this->assertTrue(class_exists('TotallyNewClass', false));
    }
}
