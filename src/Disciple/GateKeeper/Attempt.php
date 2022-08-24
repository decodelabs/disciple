<?php

/**
 * @package Disciple
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\Disciple\GateKeeper;

use DateTime;

class Attempt
{
    protected DateTime $date;
    protected string $ip;
    protected bool $success;


    /**
     * Init with params
     */
    public function __construct(
        DateTime $date,
        string $ip,
        bool $success
    ) {
        $this->date = $date;
        $this->ip = $ip;
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
    public function getIp(): string
    {
        return $this->ip;
    }

    /**
     * Was successful
     */
    public function wasSuccessful(): bool
    {
        return $this->success;
    }
}
