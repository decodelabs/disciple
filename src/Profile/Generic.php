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
    private const array Titles = [
        'mr', 'mrs', 'miss', 'ms', 'mx', 'master', 'maid', 'madam', 'dr'
    ];

    protected(set) ?string $id;
    protected(set) ?string $email;
    protected(set) ?string $fullName;

    public ?string $firstName {
        get {
            $fullName = trim((string)$this->fullName);

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
                in_array($test, self::Titles)
            );

            return ucfirst($output);
        }
    }

    public ?string $surname {
        get {
            $fullName = trim((string)$this->fullName);

            if (empty($fullName)) {
                return null;
            }

            $parts = explode(' ', $fullName);
            return array_pop($parts);
        }
    }

    protected(set) ?string $nickName;

    protected(set) ?DateTime $registrationDate;
    protected(set) ?DateTime $lastLoginDate;

    protected(set) ?string $language;
    protected(set) ?string $country;
    protected(set) ?string $timeZone;

    /**
     * @var list<string>
     */
    protected(set) array $signifiers = [];

    /**
     * @param list<string> $signifiers
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
}
