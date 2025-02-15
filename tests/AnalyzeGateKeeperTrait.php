<?php

/**
 * @package Disciple
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\Disciple\Tests;

use DateTime;
use DecodeLabs\Disciple\GateKeeper;
use DecodeLabs\Disciple\GateKeeperTrait;

class AnalyzeGateKeeperTrait implements GateKeeper
{
    use GateKeeperTrait;

    protected function storeAttempt(
        string $identity,
        string $ip,
        ?string $agent,
        bool $success
    ): void {
    }

    protected function fetchAttempts(
        string $identity,
        DateTime $since
    ): array {
        return [];
    }
}
