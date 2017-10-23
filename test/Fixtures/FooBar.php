<?php

namespace test\Enum\Fixtures;

use Enum\Enum;

/**
 * @method static FooBar FOO()
 * @method static FooBar BAR()
 * @method static FooBar XYZ()
 * @method static FooBar FOO_BAR()
 */
class FooBar extends Enum
{
    const FOO = 'foo';
    const BAR = 'bar';
    const XYZ = 'xyz';
    const FOO_BAR = 'foo_bar';
}
