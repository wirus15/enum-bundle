<?php

declare(strict_types=1);

namespace test\Enum\Bundle\Translator;

use Enum\Bundle\Translator\HumanizeEnumTranslator;
use PHPUnit\Framework\TestCase;
use test\Enum\Fixtures\FooBar;
use test\Enum\Fixtures\OneTwo;

class HumanizeEnumTranslatorTest extends TestCase
{
    public function testTranslate()
    {
        $translator = new HumanizeEnumTranslator();

        $this->assertEquals(
            'One two three',
            $translator->translate(OneTwo::ONE_TWO_THREE(), 'pl')
        );

        $this->assertEquals(
            'Foo',
            $translator->translate(FooBar::FOO(), 'en')
        );

        $this->assertEquals(
            'Foo bar',
            $translator->translate(FooBar::FOO_BAR(), 'en')
        );
    }

    public function testCanTranslate()
    {
        $translator = new HumanizeEnumTranslator();

        $this->assertTrue($translator->canTranslate(FooBar::FOO(), 'en'));
        $this->assertTrue($translator->canTranslate(FooBar::BAR(), 'pl'));
    }
}
