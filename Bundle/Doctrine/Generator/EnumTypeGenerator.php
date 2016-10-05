<?php

namespace Enum\Bundle\Doctrine\Generator;

use Enum\Bundle\Doctrine\EnumType;
use Enum\Enum;

class EnumTypeGenerator
{
    const CLASS_PREFIX = '__Enum__';

    /**
     * @var string
     */
    private $template;

    /**
     * EnumTypeGenerator constructor.
     */
    public function __construct()
    {
        $this->template = file_get_contents(__DIR__.'/EnumType.template');
    }

    /**
     * @param string $enumClass
     * @return string
     */
    public function getTypeClassName($enumClass)
    {
        return self::CLASS_PREFIX.'\\'.$enumClass;
    }

    /**
     * @param string $name
     * @param string $enumClass
     * @return GenerationResult
     */
    public function generate($name, $enumClass)
    {
        $className = $this->getTypeClassName($enumClass);

        list($namespace, $shortClassName) = $this->divideClassName($className);

        $classContent = strtr($this->template, [
            '{{namespace}}' => $namespace,
            '{{class_name}}' => $shortClassName,
            '{{enum_class}}' => $enumClass,
            '{{type_name}}' => $name,
            '{{value_type}}' => $this->findValueType($enumClass),
        ]);

        return new GenerationResult($className, $classContent);
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

    /**
     * @param string $enumClass
     * @return string
     */
    private function findValueType($enumClass)
    {
        foreach (Enum::values($enumClass) as $value) {
            if (!is_int($value)) {
                return EnumType::ENUM_STRING;
            }
        }

        return EnumType::ENUM_INT;
    }
}
