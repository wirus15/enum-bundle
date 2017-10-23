<?php

declare(strict_types=1);

namespace Enum\Bundle\Translator;

use Enum\Enum;

interface EnumTranslator
{
    public function translate(Enum $enum, ?string $locale = null): string;

    public function canTranslate(Enum $enum, ?string $locale): bool;
}
