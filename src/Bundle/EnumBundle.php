<?php

namespace Enum\Bundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class EnumBundle extends Bundle
{
    public function boot()
    {
        $this->container
            ->get('enum.type.registry')
            ->registerAutoloader();
    }
}
