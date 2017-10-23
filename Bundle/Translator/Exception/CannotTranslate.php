<?php

declare(strict_types=1);

namespace Enum\Bundle\Translator\Exception;

use Enum\Bundle\Translator\EnumTranslator;
use Enum\Enum;
use Enum\EnumException;

class CannotTranslate extends EnumException
{
    public static function enum(EnumTranslator $translator, Enum $enum): self
    {
        $message = sprintf(
            'Translator %s cannot translate enum %s.',
            get_class($translator),
            get_class($enum)
        );

        return new self($message);
    }
}
