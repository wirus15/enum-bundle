<?php

namespace Enum\Bundle\Doctrine\TypeCache;

interface EnumTypeCache
{
    /**
     * @return boolean
     */
    public function exists();

    /**
     * @return void
     */
    public function load();

    /**
     * @param string $className
     * @param string $classBody
     * @return void
     */
    public function save($className, $classBody);

    /**
     * @return void
     */
    public function clear();
}