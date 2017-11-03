<?php

declare(strict_types=1);

namespace Enum\Bundle\Translator;

use Enum\Enum;

class HumanizeEnumTranslator implements EnumTranslator
{
    public function translate(Enum $enum, ?string $locale = null): string
    {
        $humanized = trim(
            preg_replace(
                array('/([A-Z])/', "/[_\\s]+/"),
                array('_$1', ' '),
                strtolower($enum->key())
            )
        );

        return ucfirst($humanized);
    }

    public function canTranslate(Enum $enum, ?string $locale): bool
    {
        return true;
    }
}
