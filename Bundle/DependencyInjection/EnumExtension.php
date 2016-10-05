<?php

namespace Enum\Bundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\Yaml\Yaml;

class EnumExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yml');

        $this->createStorageAlias($config, $container);
        $this->registerTypesFromMainConfig($config, $container);
        $this->registerTypesFromBundles($container);
    }

    /**
     * @param array $config
     * @param ContainerBuilder $container
     */
    private function createStorageAlias(array $config, ContainerBuilder $container)
    {
        $container->setAlias('enum.type.storage', 'enum.type.storage.' . $config['type_storage']);
    }

    /**
     * @param array $config
     * @param ContainerBuilder $container
     */
    private function registerTypesFromMainConfig(array $config, ContainerBuilder $container)
    {
        $registryDefinition = $container->getDefinition('enum.type.registry');
        foreach ($config['types'] as $name => $enumClass) {
            $registryDefinition->addMethodCall('addType', [$name, $enumClass]);
        }
    }

    /**
     * @param ContainerBuilder $container
     */
    private function registerTypesFromBundles(ContainerBuilder $container)
    {
        $rootDir = $container->getParameter('kernel.root_dir');
        $registryDefinition = $container->getDefinition('enum.type.registry');
        $configFiles = [];

        foreach ($container->getParameter('kernel.bundles') as $bundle => $class) {
            $reflection = new \ReflectionClass($class);
            if (file_exists($file = dirname($reflection->getFileName()) . '/Resources/config/enum.yml')) {
                $configFiles[] = $file;
            }
            if (file_exists($file = $rootDir . sprintf('/Resources/%s/config/enum.yml', $bundle))) {
                $configFiles[] = $file;
            }
        }

        foreach ($configFiles as $file) {
            if (!is_array($config = Yaml::parse(file_get_contents($file)))) {
                continue;
            }

            foreach ($config as $name => $enumClass) {
                $registryDefinition->addMethodCall('addType', [$name, $enumClass]);
            }
        }
    }
}
