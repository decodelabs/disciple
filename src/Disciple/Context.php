<?php

/**
 * @package Disciple
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\Disciple;

use DateTime;
use DecodeLabs\Exceptional;

class Context implements Adapter, Profile
{
    /**
     * @var Adapter|null
     */
    protected $adapter;

    /**
     * Set adapter
     *
     * @return $this
     */
    public function setAdapter(Adapter $adapter): Context
    {
        $this->adapter = $adapter;
        return $this;
    }

    /**
     * Get adapter
     */
    public function getAdapter(): Adapter
    {
        if ($this->adapter === null) {
            throw Exceptional::Setup('No Disciple adapter has been registered');
        }

        return $this->adapter;
    }

    /**
     * Has adapter been registered?
     */
    public function hasAdapter(): bool
    {
        return $this->adapter !== null;
    }


    /**
     * Is user logged in?
     */
    public function isLoggedIn(): bool
    {
        return $this->getAdapter()->isLoggedIn();
    }

    /**
     * Get identity
     */
    public function getIdentity(): ?string
    {
        return $this->getAdapter()->getIdentity();
    }

    /**
     * Get profile data object
     */
    public function getProfile(): Profile
    {
        return $this->getAdapter()->getProfile();
    }

    /**
     * Get logged in user ID
     */
    public function getId(): ?string
    {
        return $this->getProfile()->getId();
    }

    /**
     * Get current user email address
     */
    public function getEmail(): ?string
    {
        return $this->getProfile()->getEmail();
    }

    /**
     * Get current user full name
     */
    public function getFullName(): ?string
    {
        return $this->getProfile()->getFullName();
    }

    /**
     * Get current user first name
     */
    public function getFirstName(): ?string
    {
        return $this->getProfile()->getFirstName();
    }

    /**
     * Get current user surname
     */
    public function getSurname(): ?string
    {
        return $this->getProfile()->getSurname();
    }

    /**
     * Get current user nickname
     */
    public function getNickName(): ?string
    {
        return $this->getProfile()->getNickName();
    }


    /**
     * Get current user registration date
     */
    public function getRegistrationDate(): ?DateTime
    {
        return $this->getProfile()->getRegistrationDate();
    }

    /**
     * Get current user last login date
     */
    public function getLastLoginDate(): ?DateTime
    {
        return $this->getProfile()->getLastLoginDate();
    }

    /**
     * Get current user language
     */
    public function getLanguage(): ?string
    {
        return $this->getProfile()->getLanguage();
    }

    /**
     * Get current user country
     */
    public function getCountry(): ?string
    {
        return $this->getProfile()->getCountry();
    }

    /**
     * Get current user timezone
     */
    public function getTimeZone(): ?string
    {
        return $this->getProfile()->getTimeZone();
    }


    /**
     * Get current user access signifiers
     */
    public function getSignifiers(): array
    {
        return $this->getProfile()->getSignifiers();
    }

    /**
     * Check if profile contains any of the following signifiers
     */
    public function isA(string ...$signifiers): bool
    {
        return $this->getAdapter()->isA(...$signifiers);
    }
}
