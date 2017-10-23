<?php

declare(strict_types=1);

namespace Enum\Bundle\Translator;

use Enum\Bundle\Translator\Exception\CannotTranslate;
use Enum\Enum;

class ChainedEnumTranslator implements EnumTranslator
{
    /** @var EnumTranslator[] */
    private $translators = [];

    public function __construct(EnumTranslator ...$translators)
    {
        $this->translators = $translators;
    }

    public function translate(Enum $enum, ?string $locale = null): string
    {
        foreach ($this->translators as $translator) {
            if ($translator->canTranslate($enum, $locale)) {
                return $translator->translate($enum, $locale);
            }
        }

        throw CannotTranslate::enum($this, $enum);
    }

    public function canTranslate(Enum $enum, ?string $locale): bool
    {
        foreach ($this->translators as $translator) {
            if ($translator->canTranslate($enum, $locale)) {
                return true;
            }
        }

        return false;
    }
}
