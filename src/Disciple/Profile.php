<?php

/**
 * @package Disciple
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\Disciple;

use DateTime;

interface Profile
{
    public ?string $id { get; }
    public ?string $email { get; }
    public ?string $fullName { get; }
    public ?string $firstName { get; }
    public ?string $surname { get; }
    public ?string $nickName { get; }

    public ?DateTime $registrationDate { get; }
    public ?DateTime $lastLoginDate { get; }

    public ?string $language { get; }
    public ?string $country { get; }
    public ?string $timeZone { get; }

    /**
     * @var list<string>
     */
    public array $signifiers { get; }
}
