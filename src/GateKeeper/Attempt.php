<?php

/**
 * @package Disciple
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\Disciple\GateKeeper;

use DateTime;
use DecodeLabs\Compass\Ip;

class Attempt
{
    protected DateTime $date;
    protected Ip $ip;
    protected bool $success;


    /**
     * Init with params
     */
    public function __construct(
        DateTime $date,
        Ip|string $ip,
        bool $success
    ) {
        $this->date = $date;
        $this->ip = Ip::parse($ip);
        $this->success = $success;
    }


    /**
     * Get date
     */
    public function getDate(): DateTime
    {
        return $this->date;
    }

    /**
     * Get IP
     */
    public function getIp(): Ip
    {
        return $this->ip;
    }

    /**
     * Get IP string
     */
    public function getIpString(): string
    {
        return (string)$this->ip;
    }

    /**
     * Was successful
     */
    public function wasSuccessful(): bool
    {
        return $this->success;
    }
}
