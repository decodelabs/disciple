<?php

/**
 * @package Disciple
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\Disciple;

use DecodeLabs\Compass\Ip;

interface Client
{
    public string $protocol { get; }
    public Ip $ip { get; }
    public string $ipString { get; }
    public ?string $agent { get; }
}
