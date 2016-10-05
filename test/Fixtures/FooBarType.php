<?php

namespace test\Enum\Fixtures;

use Enum\Bundle\Doctrine\EnumType;

class FooBarType extends EnumType
{
    /**
     * @return string
     */
    protected function getEnumClass()
    {
        return FooBar::class;
    }

    /**
     * @return string
     */
    protected function getValueType()
    {
        return self::ENUM_STRING;
    }

    /**
     * Gets the name of this type.
     *
     * @return string
     */
    public function getName()
    {
        return 'foobar';
    }
}