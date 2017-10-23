<?php

declare(strict_types=1);

namespace Enum\Bundle\Translator;

use Enum\Bundle\Translator\Exception\CannotTranslate;
use Enum\Enum;
use Symfony\Component\Translation\Translator;

class TranslatorEnumTranslator implements EnumTranslator
{
    const DEFAULT_TRANSLATION_DOMAIN = 'enum';

    /** @var string */
    private $translationDomain = self::DEFAULT_TRANSLATION_DOMAIN;

    /** @var array */
    private $prefixes = [];

    /** @var Translator */
    private $translator;

    public function __construct(Translator $translator)
    {
        $this->translator = $translator;
    }

    public function translate(Enum $enum, ?string $locale = null): string
    {
        if (!$this->canTranslate($enum, $locale)) {
            throw CannotTranslate::enum($this, $enum);
        }

        return $this->translator->trans(
            $this->resolveTranslationKey($enum),
            [],
            $this->translationDomain,
            $locale
        );
    }

    public function setTranslationDomain(string $translationDomain)
    {
        $this->translationDomain = $translationDomain;
    }

    public function registerEnumPrefix(string $enumClass, string $prefix)
    {
        $this->prefixes[$enumClass] = $prefix;
    }

    private function resolveTranslationKey(Enum $enum): string
    {
        $prefix = $this->resolvePrefix($enum);

        return sprintf('%s.%s', $prefix, $enum->value());
    }

    private function resolvePrefix(Enum $enum): string
    {
        $enumClass = get_class($enum);

        if (isset($this->prefixes[$enumClass])) {
            return $this->prefixes[$enumClass];
        }

        $reflection = new \ReflectionClass($enumClass);
        $shortClass = $reflection->getShortName();

        return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $shortClass));
    }

    public function canTranslate(Enum $enum, ?string $locale): bool
    {
        $locale = $locale ?? $this->translator->getLocale();
        $catalogue = $this->translator->getCatalogue($locale);

        return $catalogue->has(
            $this->resolveTranslationKey($enum),
            $this->translationDomain
        );
    }
}
