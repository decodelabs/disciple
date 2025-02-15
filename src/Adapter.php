<?php

/**
 * @package Disciple
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\Disciple;

interface Adapter
{
    public ?string $identity { get; }
    public Profile $profile { get; }
    public Client $client { get; }

    public function isLoggedIn(): bool;

    public function isA(
        string ...$signifiers
    ): bool;
}
