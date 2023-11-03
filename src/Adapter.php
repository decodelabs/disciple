<?php

/**
 * @package Disciple
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\Disciple;

interface Adapter
{
    public function isLoggedIn(): bool;

    public function getIdentity(): ?string;
    public function getProfile(): Profile;
    public function getClient(): Client;

    public function isA(
        string ...$signifiers
    ): bool;
}
