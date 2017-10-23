<?php

declare(strict_types=1);

namespace test\Enum\Bundle\Translator;

use Enum\Bundle\Translator\TranslatorEnumTranslator;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Translation\MessageCatalogue;
use Symfony\Component\Translation\Translator;
use test\Enum\Fixtures\FooBar;

class TranslatorEnumTranslatorTest extends TestCase
{
    /** @var Translator */
    private $translator;

    protected function setUp()
    {
        $this->translator = \Mockery::mock(Translator::class);
    }

    public function testCanTranslate()
    {
        $plCatalogue = \Mockery::mock(MessageCatalogue::class);
        $enCatalogue = \Mockery::mock(MessageCatalogue::class);

        $this->translator->allows()->getCatalogue('pl')->andReturn($plCatalogue);
        $this->translator->allows()->getCatalogue('en')->andReturn($enCatalogue);

        $plCatalogue->allows()->has('foo_bar.foo', 'enum')->andReturn(true);
        $plCatalogue->allows()->has('foo_bar.bar', 'enum')->andReturn(true);
        $enCatalogue->allows()->has('foo_bar.xyz', 'enum')->andReturn(false);
        $enCatalogue->allows()->has('foo_bar.xyz', 'yolo')->andReturn(true);


        $translator = new TranslatorEnumTranslator($this->translator);

        $this->assertTrue($translator->canTranslate(FooBar::FOO(), 'pl'));
        $this->assertTrue($translator->canTranslate(FooBar::BAR(), 'pl'));
        $this->assertFalse($translator->canTranslate(FooBar::XYZ(), 'en'));

        $translator->setTranslationDomain('yolo');
        $this->assertTrue($translator->canTranslate(FooBar::XYZ(), 'en'));
    }

    public function testCanTranslateWithCustomDomain()
    {
        $catalogue = \Mockery::mock(MessageCatalogue::class);
        $catalogue->allows()->has('foo_bar.foo', 'yolo')->andReturn(true);
        $catalogue->allows()->has('foo_bar.bar', 'yolo')->andReturn(false);

        $this->translator->allows()->getCatalogue('pl')->andReturn($catalogue);

        $translator = new TranslatorEnumTranslator($this->translator);
        $translator->setTranslationDomain('yolo');

        $this->assertTrue($translator->canTranslate(FooBar::FOO(), 'pl'));
        $this->assertFalse($translator->canTranslate(FooBar::BAR(), 'pl'));
    }

    public function testTranslate()
    {
        $catalogue = \Mockery::mock(MessageCatalogue::class);
        $catalogue->allows()->has('foo_bar.foo', 'enum')->andReturn(true);
        $catalogue->allows()->has('foo_bar.bar', 'enum')->andReturn(true);

        $this->translator->allows()->getCatalogue('pl')->andReturn($catalogue);
        $this->translator->allows()->trans('foo_bar.foo', [], 'enum', 'pl')->andReturn('some foo');
        $this->translator->allows()->trans('foo_bar.bar', [], 'enum', 'pl')->andReturn('some bar');

        $translator = new TranslatorEnumTranslator($this->translator);
        $this->assertEquals('some foo', $translator->translate(FooBar::FOO(), 'pl'));
        $this->assertEquals('some bar', $translator->translate(FooBar::BAR(), 'pl'));
    }

    public function testTranslateWithCustomDomain()
    {
        $catalogue = \Mockery::mock(MessageCatalogue::class);
        $catalogue->allows()->has('foo_bar.foo', 'yolo')->andReturn(true);
        $catalogue->allows()->has('foo_bar.bar', 'yolo')->andReturn(true);

        $this->translator->allows()->getCatalogue('pl')->andReturn($catalogue);
        $this->translator->allows()->trans('foo_bar.foo', [], 'yolo', 'pl')->andReturn('some foo');
        $this->translator->allows()->trans('foo_bar.bar', [], 'yolo', 'pl')->andReturn('some bar');

        $translator = new TranslatorEnumTranslator($this->translator);
        $translator->setTranslationDomain('yolo');

        $this->assertEquals('some foo', $translator->translate(FooBar::FOO(), 'pl'));
        $this->assertEquals('some bar', $translator->translate(FooBar::BAR(), 'pl'));
    }

    public function testTranslateWithDefinedPrefixes()
    {
        $catalogue = \Mockery::mock(MessageCatalogue::class);
        $catalogue->allows()->has('foobar.prefix.foo', 'enum')->andReturn(true);
        $catalogue->allows()->has('foobar.prefix.bar', 'enum')->andReturn(true);

        $this->translator->allows()->getCatalogue('pl')->andReturn($catalogue);
        $this->translator->allows()->trans('foobar.prefix.foo', [], 'enum', 'pl')->andReturn('some foo');
        $this->translator->allows()->trans('foobar.prefix.bar', [], 'enum', 'pl')->andReturn('some bar');

        $translator = new TranslatorEnumTranslator($this->translator);
        $translator->registerEnumPrefix(FooBar::class, 'foobar.prefix');
        $this->assertEquals('some foo', $translator->translate(FooBar::FOO(), 'pl'));
        $this->assertEquals('some bar', $translator->translate(FooBar::BAR(), 'pl'));
    }

    protected function tearDown()
    {
        \Mockery::close();
    }
}
