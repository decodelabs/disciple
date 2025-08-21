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
    public protected(set) DateTime $date;
    public protected(set) Ip $ip;
    public string $ipString { get => (string)$this->ip; }
    public protected(set) bool $success;


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
     * Was successful
     */
    public function wasSuccessful(): bool
    {
        return $this->success;
    }
}
