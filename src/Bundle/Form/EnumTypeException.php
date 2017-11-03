<?php

declare(strict_types=1);

namespace Enum\Bundle\Form;

class EnumTypeException extends \Exception
{
    public static function notAnEnum($value)
    {
        return new self("Given value ($value) is not an enum.");
    }
}
