<?php

namespace test\Enum\Bundle\Doctrine;

use Enum\Bundle\Doctrine\EnumTypeRegistry;
use Enum\Bundle\Doctrine\EnumTypeStorage;
use Enum\Bundle\Doctrine\Generator\EnumTypeGenerator;

class EnumTypeRegistryTest extends \PHPUnit_Framework_TestCase
{
    public function testItRegistersAutoloaderDuringInstantiation()
    {
        $autoloadersCount = count(spl_autoload_functions());

        new EnumTypeRegistry(
            \Mockery::mock(EnumTypeGenerator::class),
            \Mockery::mock(EnumTypeStorage::class)
        );

        $this->assertEquals($autoloadersCount + 1, count(spl_autoload_functions()));
    }
}
