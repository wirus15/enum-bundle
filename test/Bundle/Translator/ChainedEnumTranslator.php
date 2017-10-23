<?php

declare(strict_types=1);

namespace test\Enum\Bundle\Translator;

use Enum\Bundle\Translator\ChainedEnumTranslator;
use Enum\Bundle\Translator\EnumTranslator;
use Enum\Enum;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;
use test\Enum\Fixtures\FooBar;

class ChainedEnumTranslatorTest extends TestCase
{
    /** @var EnumTranslator */
    private $fooTranslator;

    /** @var EnumTranslator */
    private $barTranslator;

    /** @var EnumTranslator */
    private $xyzTranslator;

    protected function setUp()
    {
        $this->fooTranslator = $this->createTranslatorMock(FooBar::FOO());
        $this->barTranslator = $this->createTranslatorMock(FooBar::BAR());
        $this->xyzTranslator = $this->createTranslatorMock(FooBar::XYZ());
    }

    public function testCanTranslate()
    {
        $translator = new ChainedEnumTranslator(
            $this->fooTranslator,
            $this->barTranslator,
            $this->xyzTranslator
        );

        $this->assertTrue($translator->canTranslate(FooBar::FOO(), 'pl'));
        $this->assertTrue($translator->canTranslate(FooBar::BAR(), 'pl'));
        $this->assertFalse($translator->canTranslate(FooBar::XYZ(), 'en'));
    }

    public function testTranslate()
    {
        $translator = new ChainedEnumTranslator(
            $this->fooTranslator,
            $this->barTranslator,
            $this->xyzTranslator
        );

        $this->assertEquals('foo translation', $translator->translate(FooBar::FOO(), 'pl'));
        $this->assertEquals('bar translation', $translator->translate(FooBar::BAR(), 'pl'));
        $this->assertEquals('xyz translation', $translator->translate(FooBar::XYZ(), 'pl'));
    }

    private function createTranslatorMock(Enum $enum): MockInterface
    {
        $translator = \Mockery::mock(EnumTranslator::class);
        $translator->allows()
            ->canTranslate(\Mockery::type(FooBar::class), 'pl')
            ->andReturnUsing(function (FooBar $value, string $language) use ($enum) {
                return $value->is($enum);
            });

        $translator->allows()
            ->translate(\Mockery::type(FooBar::class), 'pl')
            ->andReturnUsing(function (FooBar $value, string $language) use ($enum) {
                return sprintf('%s translation', $value->value());
            });

        $translator->allows()
            ->canTranslate(\Mockery::type(FooBar::class), 'en')
            ->andReturn(false);

        return $translator;
    }
}
