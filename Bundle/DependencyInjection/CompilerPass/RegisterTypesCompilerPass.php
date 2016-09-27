<?php

namespace Enum\Bundle\DependencyInjection\CompilerPass;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Yaml\Yaml;

class RegisterTypesCompilerPass implements CompilerPassInterface
{
    /**
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        $rootDir = $container->getParameter('kernel.root_dir');
        $registryDefinition = $container->getDefinition('enum.type.storage');
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