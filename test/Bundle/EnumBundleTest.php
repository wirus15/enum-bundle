<?php

namespace test\Enum\Bundle;

use Enum\Bundle\EnumBundle;
use Symfony\Component\DependencyInjection\ContainerInterface;

class EnumBundleTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var EnumBundle
     */
    private $bundle;

    /**
     * @var ContainerInterface
     */
    private $container;

    protected function setUp()
    {
        $this->container = \Mockery::mock(ContainerInterface::class);
        $this->bundle = new EnumBundle();
        $this->bundle->setContainer($this->container);
    }

    public function testBundleCallsForTypeRegistryDuringBoot()
    {
        $this->container
            ->shouldReceive('get')
            ->with('enum.type.registry')
            ->once();

        $this->bundle->boot();
    }
}
