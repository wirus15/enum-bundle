<?php

namespace Enum\Bundle\DependencyInjection\CompilerPass;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class RegisterTypesCompilerPass implements CompilerPassInterface
{
    /**
     * You can modify the container here before it is dumped to PHP code.
     *
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        $config = $container->getExtensionConfig('enum');

        $registryDefinition = $container->findDefinition('enum.type.registry');

        foreach ($config[0]['types'] as $name => $enumClass) {
            $registryDefinition->addMethodCall('addType', [$name, $enumClass]);
        }
    }
}