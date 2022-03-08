<?php

/**
 * @package Disciple
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\Disciple\Client;

use DecodeLabs\Disciple\Client;

class Generic implements Client
{
    /**
     * @var string
     */
    protected $protocol;

    /**
     * @var string
     */
    protected $ip;

    /**
     * @var string|null
     */
    protected $agent;

    /**
     * Init with details
     */
    public function __construct(
        string $protocol,
        string $ip,
        ?string $agent
    ) {
        $this->protocol = $protocol;
        $this->ip = $ip;
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
