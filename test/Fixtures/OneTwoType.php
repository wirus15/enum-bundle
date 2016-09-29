<?php

namespace test\Enum\Fixtures;

use Enum\Bundle\Doctrine\EnumType;

class OneTwoType extends EnumType
{
    /**
     * @return string
     */
    protected function getEnumClass()
    {
        return OneTwo::class;
    }

    /**
     * @return string
     */
    protected function getValueType()
    {
        return self::ENUM_INT;
    }

    /**
     * Gets the name of this type.
     *
     * @return string
     */
    public function getName()
    {
        return 'onetwo';
    }
}