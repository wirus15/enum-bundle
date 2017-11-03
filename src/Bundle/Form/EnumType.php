<?php

declare(strict_types=1);

namespace Enum\Bundle\Form;

use Enum\Bundle\Translator\EnumTranslator;
use Enum\Enum;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Exception\InvalidConfigurationException;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EnumType extends AbstractType
{
    /** @var EnumTranslator */
    private $translator;

    public function __construct(EnumTranslator $translator)
    {
        $this->translator = $translator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (empty($options['choices'])) {
            throw new InvalidConfigurationException(
                'You must provide "choices" option for EnumType form type.'
            );
        }

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            if ($event->getData() !== null && !$event->getData() instanceof Enum) {
                throw EnumTypeException::notAnEnum($event->getData());
            }
        });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'choices_as_values' => true,
            'choice_value' => function (?Enum $value) {
                return $value ? $value->value() : null;
            },
            'choice_label' => function (?Enum $value) {
                return $value ? $this->translator->canTranslate($value, null) ?
                    $this->translator->translate($value) :
                    (string) $value : null;
            },
            'choice_translation_domain' => 'enum',
            'empty_data' => null,
        ]);
    }

    public function getParent()
    {
        return ChoiceType::class;
    }
}
