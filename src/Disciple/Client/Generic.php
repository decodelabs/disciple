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
    protected string $protocol;
    protected Ip $ip;
    protected ?string $agent;

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

    /**
     * Get protocol string
     */
    public function getProtocol(): string
    {
        return $this->protocol;
    }

    /**
     * Get IP string
     */
    public function getIpString(): string
    {
        return (string)$this->ip;
    }

    /**
     * Get IP object
     */
    public function getIp(): Ip
    {
        return $this->ip;
    }


    /**
     * Get user agent string
     */
    public function getAgent(): ?string
    {
        return $this->agent;
    }
}
