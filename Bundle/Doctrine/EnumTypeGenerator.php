<?php

namespace Enum\Bundle\Doctrine;

use Enum\Bundle\Doctrine\TypeCache\EnumTypeCache;

class EnumTypeGenerator
{
    const CLASS_PREFIX = '__Enum__';

    /**
     * @var EnumTypeCache
     */
    private $cache;

    /**
     * @var string
     */
    private $template;

    /**
     * EnumTypeGenerator constructor.
     * @param EnumTypeCache $cache
     */
    public function __construct(EnumTypeCache $cache = null)
    {
        $this->cache = $cache;
        $this->template = require(__DIR__.'/EnumType.template');

        if ($this->cache) {
            $this->cache->load();
        }
    }

    /**
     * @param string $name
     * @param string $enumClass
     * @return string
     */
    public function generate($name, $enumClass)
    {
        $className = self::CLASS_PREFIX.'\\'.$enumClass;

        if (class_exists($className, false)) {
            return $className;
        }

        list($namespace, $shortClassName) = $this->divideClassName($className);
        $class = strtr($this->template, [
            '{{namespace}}' => $namespace,
            '{{class_name}}' => $shortClassName,
            '{{enum_class}}' => $enumClass,
            '{{type_name}}' => $name,
        ]);

        eval($class);

        if ($this->cache) {
            $this->cache->save($className, $class);
        }

        return $className;
    }

    /**
     * @param string $className
     * @return array
     */
    private function divideClassName($className)
    {
        $exploded = explode('\\', $className);
        $shortClassName = array_pop($exploded);
        $namespace = implode('\\', $exploded);

        return [$namespace, $shortClassName];
    }
}
