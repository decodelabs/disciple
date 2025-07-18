<?php

/**
 * @package Disciple
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\Disciple\Client;

use DecodeLabs\Compass\Ip;
use DecodeLabs\Disciple\Client;

class Generic implements Client
{
    public protected(set) string $protocol;
    public protected(set) Ip $ip;
    public string $ipString { get => (string)$this->ip; }
    public protected(set) ?string $agent;

    /**
     * Init with details
     */
    public function __construct(
        string $protocol,
        Ip|string $ip,
        ?string $agent
    ) {
        $this->protocol = $protocol;
        $this->ip = Ip::parse($ip);
        $this->agent = $agent;
    }
}
