<?php

namespace Enum\Bundle\DependencyInjection\CompilerPass;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\Yaml\Yaml;

class RegisterTypesCompilerPass implements CompilerPassInterface
{
    /**
     * You can modify the container here before it is dumped to PHP code.
     *
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        $registryDefinition = $container->findDefinition('enum.type.registry');

        $this->registerTypesFromGlobalConfig($registryDefinition, $container);
        $this->registerTypesFromBundles($registryDefinition, $container);
    }

    private function registerTypesFromGlobalConfig(Definition $registryDefinition, ContainerBuilder $container)
    {
        $config = $container->getExtensionConfig('enum');

        if (!isset($config[0]['types'])) {
            return;
        }

        foreach ($config[0]['types'] as $name => $enumClass) {
            $registryDefinition->addMethodCall('addType', [$name, $enumClass]);
        }
    }

    private function registerTypesFromBundles(Definition $registryDefinition, ContainerBuilder $container)
    {
        $rootDir = $container->getParameter('kernel.root_dir');
        $configFiles = [];

        foreach ($container->getParameter('kernel.bundles') as $bundle => $class) {
            $reflection = new \ReflectionClass($class);
            if (file_exists($file = dirname($reflection->getFileName()).'/Resources/config/enum.yml')) {
                $configFiles[] = $file;
            }
            if (file_exists($file = $rootDir.sprintf('/Resources/%s/config/enum.yml', $bundle))) {
                $configFiles[] = $file;
            }
        }

        foreach ($configFiles as $file) {
            $config = Yaml::parse(file_get_contents($file));
            foreach ($config as $name => $enumClass) {
                $registryDefinition->addMethodCall('addType', [$name, $enumClass]);
            }
        }
    }
}