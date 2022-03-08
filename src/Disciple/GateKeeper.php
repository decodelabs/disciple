<?php

/**
 * @package Disciple
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\Disciple;

interface GateKeeper
{
    public function approveLogin(
        string $identity,
        ?callable $failHandler = null
    ): bool;

    public function reportLogin(
        string $identity,
        bool $success
    ): void;
}
