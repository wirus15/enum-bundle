<?php

namespace Enum\Bundle\Doctrine;

class EnumTypeAutoloader
{
    /**
     * @var EnumTypeStorage
     */
    private $typeStorage;

    /**
     * EnumAutoloader constructor.
     * @param EnumTypeStorage $typeStorage
     */
    public function __construct(EnumTypeStorage $typeStorage)
    {
        $this->typeStorage = $typeStorage;
    }

    /**
     * @return bool
     */
    public function register()
    {
        return spl_autoload_register([$this, 'loadClass']);
    }

    /**
     * @param string $className
     * @return bool
     */
    public function loadClass($className)
    {
        if ($this->typeStorage->exists($className)) {
            return $this->typeStorage->load($className);
        }

        return false;
    }
}