<?php

namespace test\Enum\Bundle;

use Enum\Bundle\EnumBundle;
use Symfony\Component\DependencyInjection\ContainerInterface;

class EnumBundleTest extends \PHPUnit_Framework_TestCase
{
    public function testBundleCallsForTypeRegistryDuringBoot()
    {
        $container = \Mockery::mock(ContainerInterface::class);
        $container
            ->shouldReceive('get')
            ->with('enum.type.registry')
            ->once();

        $bundle = new EnumBundle();
        $bundle->setContainer($container);
        $bundle->boot();
    }
}
