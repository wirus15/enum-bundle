<?php

namespace test\Enum\Bundle\DependencyInjection;

use Doctrine\Common\Annotations\Annotation\Enum;
use Doctrine\Common\Util\Debug;
use Enum\Bundle\DependencyInjection\EnumExtension;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class EnumExtensionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var EnumExtension
     */
    private $extension;

    /**
     * @var ContainerBuilder
     */
    private $container;

    /**
     * @var vfsStreamDirectory
     */
    private $root;

    protected function setUp()
    {
        $this->root = vfsStream::setup('enum');
        vfsStream::create([
            'enum' => [
                'app' => [
                    'config' => [],
                ],
                'var' => [
                    'cache' => [],
                ],
            ]
        ], $this->root);

        $this->extension = new EnumExtension();
        $this->container = new ContainerBuilder();
        $this->container->setParameter('kernel.root_dir', $this->root->path('enum/app'));
        $this->container->setParameter('kernel.bundles', []);
        $this->container->setParameter('kernel.cache_dir', $this->root->path('enum/var/cache'));
        $this->container->registerExtension($this->extension);
        $this->container->loadFromExtension($this->extension->getAlias());
    }

    public function testDefaultTypeStorageAliasIsFile()
    {
        $this->container->compile();

        $this->assertEquals(
            'enum.type.storage.file',
            $this->container->getAlias('enum.type.storage')
        );
    }

    public function testTypeStorageAlias()
    {
        $this->container->prependExtensionConfig('enum', [
            'type_storage' => 'memory',
        ]);

        $this->container->compile();

        $this->assertEquals(
            'enum.type.storage.memory',
            $this->container->getAlias('enum.type.storage')
        );
    }
}
