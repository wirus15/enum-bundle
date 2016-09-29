<?php

namespace test\Enum\Bundle\Doctrine\TypeStorage;

use Enum\Bundle\Doctrine\TypeStorage\MemoryEnumTypeStorage;
use test\Enum\Fixtures\FooBarType;

class MemoryEnumTypeStorageTest extends \PHPUnit_Framework_TestCase
{
    public function testItSavesTypes()
    {
        $sut = new MemoryEnumTypeStorage();
        $this->assertFalse($sut->exists(FooBarType::class));

        $sut->save(FooBarType::class, 'foo_bar_type_content');
        $this->assertTrue($sut->exists(FooBarType::class));
    }
}
