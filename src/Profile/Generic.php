<?php

/**
 * @package Disciple
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\Disciple\Profile;

use DateTime;
use DecodeLabs\Disciple\Profile;

class Generic implements Profile
{
    protected ?string $id;
    protected ?string $email;
    protected ?string $fullName;
    protected ?string $nickName;

    protected ?DateTime $registrationDate;
    protected ?DateTime $lastLoginDate;

    protected ?string $language;
    protected ?string $country;
    protected ?string $timeZone;

    /**
     * @var array<string>
     */
    protected array $signifiers = [];

    /**
     * @param array<string> $signifiers
     */
    public function __construct(
        ?string $id,
        ?string $email = null,
        ?string $fullName = null,
        ?string $nickName = null,
        ?DateTime $registrationDate = null,
        ?DateTime $lastLoginDate = null,
        ?string $language = null,
        ?string $country = null,
        ?string $timeZone = null,
        array $signifiers = []
    ) {
        $this->id = $id;
        $this->email = $email;
        $this->fullName = $fullName;
        $this->nickName = $nickName;
        $this->registrationDate = $registrationDate;
        $this->lastLoginDate = $lastLoginDate;
        $this->language = $language;
        $this->country = $country;
        $this->timeZone = $timeZone;
        $this->signifiers = $signifiers;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function getFullName(): ?string
    {
        return $this->fullName;
    }

    public function getFirstName(): ?string
    {
        static $titles = ['mr', 'mrs', 'miss', 'ms', 'mx', 'master', 'maid', 'madam', 'dr'];
        $fullName = trim((string)$this->getFullName());

        if (empty($fullName)) {
            return null;
        }

        if (false === ($parts = preg_split('/\s+|\./', $fullName))) {
            $parts = explode(' ', $fullName);
        }

        do {
            $output = (string)array_shift($parts);
            $test = strtolower(str_replace([',', '.', '-'], '', $output));
        } while (
            count($parts) > 1 &&
            in_array($test, $titles)
        );

        return ucfirst($output);
    }

    public function getSurname(): ?string
    {
        $fullName = trim((string)$this->getFullName());

        if (empty($fullName)) {
            return null;
        }

        $parts = explode(' ', $fullName);
        return array_pop($parts);
    }

    public function getNickName(): ?string
    {
        return $this->nickName;
    }

    public function getRegistrationDate(): ?DateTime
    {
        return $this->registrationDate;
    }

    public function getLastLoginDate(): ?DateTime
    {
        return $this->lastLoginDate;
    }

    public function getLanguage(): ?string
    {
        return $this->language;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function getTimeZone(): ?string
    {
        return $this->timeZone;
    }

    public function getSignifiers(): array
    {
        return $this->signifiers;
    }
}
