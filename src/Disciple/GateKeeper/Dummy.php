<?php

/**
 * @package Disciple
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\Disciple\GateKeeper;

use DecodeLabs\Disciple\GateKeeper;

class Dummy implements GateKeeper
{
    public function approveLogin(
        string $identity,
        ?callable $failHandler = null
    ): bool {
        return true;
    }

    public function reportLogin(
        string $identity,
        bool $success
    ): void {
        // Do nothing
    }
}
