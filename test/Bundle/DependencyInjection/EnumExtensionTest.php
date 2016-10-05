<?php

namespace test\Enum\Bundle\DependencyInjection;

use Enum\Bundle\DependencyInjection\EnumExtension;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use test\Enum\Fixtures\FooBarType;
use test\Enum\Fixtures\OneTwoType;

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
            'app' => [
                'config' => [],
            ],
            'var' => [
                'cache' => [],
            ],
            'src' => [
                'FooBundle' => [
                    'Resources' => [
                        'config' => [
                            'enum.yml' => "foobar: test\\Enum\\Fixtures\\FooBarType",
                        ],
                    ],
                    'FooBundle.php' => '<?php class FooBundle {}',
                ],
                'BarBundle' => [
                    'Resources' => [
                        'config' => [
                            'enum.yml' => "onetwo: test\\Enum\\Fixtures\\OneTwoType\nthreefour: test\\Enum\\Fixtures\\OneTwoType",
                        ],
                    ],
                    'BarBundle.php' => '<?php class BarBundle {}',
                ],
            ],
        ], $this->root);

        $this->extension = new EnumExtension();
        $this->container = new ContainerBuilder();
        $this->container->setParameter('kernel.root_dir', $this->root->getChild('app')->path());
        $this->container->setParameter('kernel.bundles', []);
        $this->container->setParameter('kernel.cache_dir', $this->root->getChild('var/cache')->path());
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

    /**
     * @dataProvider aliasConfigProvider
     * @param string $type
     * @param string $alias
     */
    public function testTypeStorageAlias($type, $alias)
    {
        $this->container->prependExtensionConfig('enum', [
            'type_storage' => $type,
        ]);

        $this->container->compile();

        $this->assertEquals($alias, $this->container->getAlias('enum.type.storage'));
    }

    public function aliasConfigProvider()
    {
        return [
            ['file', 'enum.type.storage.file'],
            ['memory', 'enum.type.storage.memory'],
        ];
    }

    public function testEnumTypeDefinitionsFromMainConfig()
    {
        $this->container->prependExtensionConfig('enum', [
            'types' => [
                'foobar' => FooBarType::class,
                'onetwo' => OneTwoType::class,
            ],
        ]);

        $this->container->compile();

        $this->assertAddTypeCalls([
            'foobar' => FooBarType::class,
            'onetwo' => OneTwoType::class,
        ]);
    }

    public function testEnumTypeDefinitionsFromBundles()
    {
        $this->container->setParameter('kernel.bundles', [
            'FooBundle',
            'BarBundle',
        ]);

        require_once $this->root->getChild('src/FooBundle/FooBundle.php')->url();
        require_once $this->root->getChild('src/BarBundle/BarBundle.php')->url();

        $this->container->compile();

        $this->assertAddTypeCalls([
            'foobar' => FooBarType::class,
            'onetwo' => OneTwoType::class,
            'threefour' => OneTwoType::class,
        ]);
    }

    private function assertAddTypeCalls(array $calls)
    {
        $registryDefinition = $this->container->getDefinition('enum.type.registry');
        $methodCalls = $registryDefinition->getMethodCalls();

        foreach ($calls as $type => $class) {
            $this->assertContains(['addType', [$type, $class]], $methodCalls);
        }
    }
}
