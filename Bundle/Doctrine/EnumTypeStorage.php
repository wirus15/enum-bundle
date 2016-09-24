<?php

namespace Enum\Bundle\Doctrine;

interface EnumTypeStorage
{
    /**
     * @param string $className
     * @return bool
     */
    public function exists($className);

    /**
     * @param string $className
     * @param string $classContent
     */
    public function save($className, $classContent);

    /**
     * @param string $className
     * @return bool
     */
    public function load($className);
}