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
    public function getId(): ?string;
    public function getEmail(): ?string;
    public function getFullName(): ?string;
    public function getFirstName(): ?string;
    public function getSurname(): ?string;
    public function getNickName(): ?string;

    public function getRegistrationDate(): ?DateTime;
    public function getLastLoginDate(): ?DateTime;

    public function getLanguage(): ?string;
    public function getCountry(): ?string;
    public function getTimeZone(): ?string;

    /**
     * @return array<string>
     */
    public function getSignifiers(): array;
}
