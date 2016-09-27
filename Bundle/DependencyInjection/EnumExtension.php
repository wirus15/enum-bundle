<?php

namespace Enum\Bundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

class EnumExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $this->createStorageAlias($config, $container);
        $this->registerTypes($config, $container);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
    }

    /**
     * @param array $config
     * @param ContainerBuilder $container
     */
    private function createStorageAlias(array $config, ContainerBuilder $container)
    {
        $container->setAlias('enum.type.storage', 'enum.type.storage.'.$config['type_storage']);
    }

    /**
     * @param array $config
     * @param ContainerBuilder $container
     */
    private function registerTypes(array $config, ContainerBuilder $container)
    {
        $registryDefinition = $container->getDefinition('enum.type.registry');
        foreach ($config['types'] as $name => $enumClass) {
            $registryDefinition->addMethodCall('addType', [$name, $enumClass]);
        }
    }
}
