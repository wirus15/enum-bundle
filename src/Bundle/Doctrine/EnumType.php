<?php

namespace Enum\Bundle\Doctrine;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use Enum\Enum;

abstract class EnumType extends Type
{
    const ENUM_INT = 'int';
    const ENUM_STRING = 'string';

    /**
     * @return string
     */
    abstract protected function getEnumClass();

    /**
     * @return string
     */
    abstract protected function getValueType();

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
        return $this->getValueType() === self::ENUM_INT ?
            $platform->getIntegerTypeDeclarationSQL($fieldDeclaration) :
            $platform->getVarcharTypeDeclarationSQL($fieldDeclaration);
    }

    /**
     * @param mixed $value
     * @param AbstractPlatform $platform
     * @return mixed
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return ($value !== null && $value instanceof Enum) ? $value->value() : $value;
    }

    /**
     * @param mixed $value
     * @param AbstractPlatform $platform
     * @return mixed
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return Enum::get($value, $this->getEnumClass());
    }

    /**
     * {@inheritdoc}
     */
    public function requiresSQLCommentHint(AbstractPlatform $platform)
    {
        return true;
    }
}
