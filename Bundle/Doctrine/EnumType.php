<?php

namespace Enum\Bundle\Doctrine;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use Enum\Enum;

abstract class EnumType extends Type
{
    /**
     * @return string
     */
    abstract protected function getEnumClass();

    /**
     * Gets the SQL declaration snippet for a field of this type.
     *
     * @param array $fieldDeclaration The field declaration.
     * @param AbstractPlatform $platform The currently used database platform.
     *
     * @return string
     */
    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        $items = call_user_func([$this->getEnumClass(), 'getItems']);
        $values = array_map(function(Enum $enum) {
            return '\''.$enum->getValue().'\'';
        }, $items);

        return sprintf(
            'ENUM(%s) COMMENT \'(DC2Type:%s)\'',
            implode(', ', $values),
            $this->getName());
    }

    /**
     * @param mixed $value
     * @param AbstractPlatform $platform
     * @return mixed
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return ($value !== null && $value instanceof Enum) ? $value->getValue() : $value;
    }

    /**
     * @param mixed $value
     * @param AbstractPlatform $platform
     * @return mixed
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return call_user_func([$this->getEnumClass(), 'get'], $value);
    }
}
