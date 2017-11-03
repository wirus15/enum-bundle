<?php

namespace test\Enum\Bundle;

use Enum\Bundle\Doctrine\EnumTypeRegistry;
use Enum\Bundle\EnumBundle;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\Container;

class EnumBundleTest extends TestCase
{
    /**
     * @var EnumBundle
     */
    private $bundle;

    /**
     * @var EnumTypeRegistry
     */
    private $registry;

    protected function setUp()
    {
        $this->registry = \Mockery::mock(EnumTypeRegistry::class);

        $container = new Container();
        $container->set('enum.type.registry', $this->registry);

        $this->bundle = new EnumBundle();
        $this->bundle->setContainer($container);
    }

    public function testBundleCallsForTypeRegistryDuringBoot()
    {
        $this->registry
            ->shouldReceive('registerAutoloader')
            ->once();

        $this->bundle->boot();
    }
}
