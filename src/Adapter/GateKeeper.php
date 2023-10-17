<?php

/**
 * @package Disciple
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\Disciple\Adapter;

use DecodeLabs\Disciple\Adapter;
use DecodeLabs\Disciple\GateKeeper as Handler;

interface GateKeeper extends Adapter
{
    public function getGateKeeper(): Handler;
}
