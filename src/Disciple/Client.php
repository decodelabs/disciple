<?php

/**
 * @package Disciple
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\Disciple;

interface Client
{
    public function getProtocol(): string;
    public function getIpString(): string;
    //public function getIp(): Ip;

    public function getAgent(): ?string;
}
