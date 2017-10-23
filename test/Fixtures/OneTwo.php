<?php

namespace test\Enum\Fixtures;

use Enum\Enum;

/**
 * @method static OneTwo ONE()
 * @method static OneTwo TWO()
 * @method static OneTwo THREE()
 * @method static OneTwo ONE_TWO_THREE()
 */
class OneTwo extends Enum
{
    const ONE = 1;
    const TWO = 2;
    const THREE = 3;
    const ONE_TWO_THREE = 123;
}
