<?php

/**
 * @package Disciple
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\Disciple;

use DateTime;
use DecodeLabs\Compass\Ip;
use DecodeLabs\Disciple;
use DecodeLabs\Disciple\Adapter\GateKeeper as GateKeeperAdapter;
use DecodeLabs\Disciple\GateKeeper\Dummy as DummyGateKeeper;
use DecodeLabs\Exceptional;
use DecodeLabs\Veneer;

class Context
{
    protected ?Adapter $adapter = null;
    protected ?GateKeeper $gateKeeper = null;

    /**
     * Set adapter
     *
     * @return $this
     */
    public function setAdapter(
        Adapter $adapter
    ): static {
        $this->adapter = $adapter;
        return $this;
    }

    /**
     * Get adapter
     */
    public function getAdapter(): Adapter
    {
        if ($this->adapter === null) {
            throw Exceptional::Setup(
                message: 'No Disciple adapter has been registered'
            );
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
        return $this->getAdapter()->identity;
    }

    /**
     * Get profile data object
     */
    public function getProfile(): Profile
    {
        return $this->getAdapter()->profile;
    }

    /**
     * Get client data object
     */
    public function getClient(): Client
    {
        return $this->getAdapter()->client;
    }


    /**
     * Get user ID if logged in
     */
    public function getId(): ?string
    {
        return $this->getProfile()->id;
    }

    /**
     * Get ID of logged in user
     */
    public function getActiveId(): string
    {
        if (!$this->isLoggedIn()) {
            throw Exceptional::Runtime(
                message: 'User is not logged in'
            );
        }

        $id = $this->getProfile()->id;

        if ($id === null) {
            throw Exceptional::Runtime(
                message: 'User does not have an ID'
            );
        }

        return (string)$id;
    }

    /**
     * Get current user email address
     */
    public function getEmail(): ?string
    {
        return $this->getProfile()->email;
    }

    /**
     * Get current user full name
     */
    public function getFullName(): ?string
    {
        return $this->getProfile()->fullName;
    }

    /**
     * Get current user first name
     */
    public function getFirstName(): ?string
    {
        return $this->getProfile()->firstName;
    }

    /**
     * Get current user surname
     */
    public function getSurname(): ?string
    {
        return $this->getProfile()->surname;
    }

    /**
     * Get current user nickname
     */
    public function getNickName(): ?string
    {
        return $this->getProfile()->nickName;
    }


    /**
     * Get current user registration date
     */
    public function getRegistrationDate(): ?DateTime
    {
        return $this->getProfile()->registrationDate;
    }

    /**
     * Get current user last login date
     */
    public function getLastLoginDate(): ?DateTime
    {
        return $this->getProfile()->lastLoginDate;
    }

    /**
     * Get current user language
     */
    public function getLanguage(): ?string
    {
        return $this->getProfile()->language;
    }

    /**
     * Get current user country
     */
    public function getCountry(): ?string
    {
        return $this->getProfile()->country;
    }

    /**
     * Get current user timezone
     */
    public function getTimeZone(): ?string
    {
        return $this->getProfile()->timeZone;
    }


    /**
     * Get current user access signifiers
     *
     * @return list<string>
     */
    public function getSignifiers(): array
    {
        return $this->getProfile()->signifiers;
    }

    /**
     * Check if profile contains any of the following signifiers
     */
    public function isA(
        string ...$signifiers
    ): bool {
        return $this->getAdapter()->isA(...$signifiers);
    }


    /**
     * Get IP address
     */
    public function getIp(): Ip
    {
        return $this->getClient()->ip;
    }

    /**
     * Get IP string
     */
    public function getIpString(): string
    {
        return $this->getClient()->ipString;
    }

    /**
     * Get user agent
     */
    public function getAgent(): ?string
    {
        return $this->getClient()->agent;
    }



    /**
     * Get GateKeeper
     */
    public function getGateKeeper(): GateKeeper
    {
        if ($this->gateKeeper) {
            return $this->gateKeeper;
        }

        $adapter = $this->getAdapter();

        if ($adapter instanceof GateKeeperAdapter) {
            return $this->gateKeeper = $adapter->gateKeeper;
        }

        return $this->gateKeeper = new DummyGateKeeper();
    }
}

// Register the Veneer facade
Veneer\Manager::getGlobalManager()->register(
    Context::class,
    Disciple::class
);
