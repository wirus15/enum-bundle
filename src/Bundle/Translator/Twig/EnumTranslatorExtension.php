<?php

declare(strict_types=1);

namespace Enum\Bundle\Translator;

use Enum\Enum;

class EnumTranslatorExtension extends \Twig_Extension
{
    /** @var EnumTranslator */
    private $translator;

    public function __construct(EnumTranslator $translator)
    {
        $this->translator = $translator;
    }

    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('readable', function (Enum $enum, ?string $locale = null): string {
                if ($this->translator->canTranslate($enum, $locale)) {
                    return $this->translator->translate($enum, $locale);
                }

                return (string) $enum;
            }),
        ];
    }
}
