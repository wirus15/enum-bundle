<?php

namespace Enum\Bundle;

use Enum\Bundle\DependencyInjection\CompilerPass\RegisterTypesCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class EnumBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new RegisterTypesCompilerPass());
    }

}