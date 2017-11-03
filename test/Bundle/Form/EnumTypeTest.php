<?php

declare(strict_types=1);

namespace test\Enum\Form;

use Enum\Bundle\Form\EnumType;
use Enum\Bundle\Form\EnumTypeException;
use Enum\Bundle\Translator\EnumTranslator;
use Symfony\Component\Form\Exception\InvalidConfigurationException;
use Symfony\Component\Form\PreloadedExtension;
use Symfony\Component\Form\Test\TypeTestCase;
use test\Enum\Fixtures\FooBar;
use test\Enum\Fixtures\OneTwo;

class EnumTypeTest extends TypeTestCase
{
    private $translator;

    protected function setUp()
    {
        $this->translator = $this->createMock(EnumTranslator::class);

        parent::setUp();
    }

    protected function getExtensions()
    {
        $type = new EnumType($this->translator);

        return [
            new PreloadedExtension([$type], []),
        ];
    }

    public function testCreateType()
    {
        $form = $this->factory->create(EnumType::class, null, [
            'choices' => FooBar::all(),
        ]);
        $this->assertNull($form->getData());

        $form = $this->factory->create(EnumType::class, FooBar::FOO(), [
            'choices' => FooBar::all(),
        ]);
        $this->assertEquals(FooBar::FOO(), $form->getData());

        $this->expectExceptionObject(
            new InvalidConfigurationException(
                'You must provide "choices" option for EnumType form type.'
            )
        );
        $this->factory->create(EnumType::class, null);

        $this->expectExceptionObject(EnumTypeException::notAnEnum('yolo'));
        $this->factory->create(EnumType::class, 'yolo', [
            'choices' => FooBar::all(),
        ]);
    }

    public function testSubmitData()
    {
        $form = $this->factory->create(EnumType::class, null, [
            'choices' => FooBar::all(),
        ]);

        $form->submit(FooBar::FOO);
        $this->assertTrue($form->isSynchronized());
        $this->assertEquals(FooBar::FOO(), $form->getData());

        $form = $this->factory->create(EnumType::class, null, [
            'choices' => FooBar::all(),
        ]);

        $form->submit(FooBar::FOO());
        $this->assertFalse($form->isSynchronized());
        $this->assertNull($form->getData());

        $form = $this->factory->create(EnumType::class, null, [
            'choices' => OneTwo::all(),
        ]);

        $form->submit(FooBar::FOO);
        $this->assertFalse($form->isSynchronized());
        $this->assertNull($form->getData());

        $form = $this->factory->create(EnumType::class, null, [
            'choices' => OneTwo::all(),
        ]);

        $form->submit(null);
        $this->assertTrue($form->isSynchronized());
        $this->assertNull($form->getData());
    }
}
