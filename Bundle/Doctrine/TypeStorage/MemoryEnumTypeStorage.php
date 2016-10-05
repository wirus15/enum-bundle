<?php

namespace Enum\Bundle\Doctrine\TypeStorage;

use Enum\Bundle\Doctrine\EnumTypeStorage;

class MemoryEnumTypeStorage implements EnumTypeStorage
{
    /**
     * @var array
     */
    private $classes = [];

    /**
     * @param string $name
     * @return bool
     */
    public function exists($name)
    {
        return array_key_exists($name, $this->classes);
    }

    /**
     * @param string $className
     * @param string $classContent
     */
    public function save($className, $classContent)
    {
        $this->classes[$className] = $classContent;
    }

    /**
     * @param string $className
     * @return bool
     */
    public function load($className)
    {
        if (class_exists($className, false)) {
            return true;
        }

        if (!$this->exists($className)) {
            return false;
        }

        eval($this->classes[$className]);

        return true;
    }
}