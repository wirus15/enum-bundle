<?php

namespace test\Enum\Bundle\Doctrine;

use Enum\Bundle\Doctrine\EnumTypeAutoloader;
use Enum\Bundle\Doctrine\EnumTypeStorage;

class EnumTypeAutoloaderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var EnumTypeAutoloader
     */
    private $sut;

    /**
     * @var EnumTypeStorage
     */
    private $storage;

    protected function setUp()
    {
        $this->storage = \Mockery::mock(EnumTypeStorage::class);
        $this->sut = new EnumTypeAutoloader($this->storage);
    }

    public function testItRegistersAutoloadFunction()
    {
        $this->assertTrue($this->sut->register());
        $autoloaders = spl_autoload_functions();
        $this->assertEquals([$this->sut, 'loadClass'], end($autoloaders));
    }

    public function testItLoadsClassFromStorage()
    {
        $this->storage
            ->shouldReceive('exists')
            ->with('some_class')
            ->andReturn(true);

        $this->storage
            ->shouldReceive('load')
            ->with('some_class')
            ->andReturn(true);

        $this->assertTrue($this->sut->loadClass('some_class'));
    }

    public function testItReturnsFalseIfClassDoesNotExistsInStorage()
    {
        $this->storage
            ->shouldReceive('exists')
            ->with('some_class')
            ->andReturn(false);

        $this->assertFalse($this->sut->loadClass('some_class'));
    }
}
