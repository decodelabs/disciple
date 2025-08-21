<?php

/**
 * @package Disciple
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs;

use DateTime;
use DecodeLabs\Compass\Ip;
use DecodeLabs\Disciple\Adapter;
use DecodeLabs\Disciple\Adapter\Dummy;
use DecodeLabs\Disciple\Adapter\GateKeeper as GateKeeperAdapter;
use DecodeLabs\Disciple\Client;
use DecodeLabs\Disciple\GateKeeper;
use DecodeLabs\Disciple\GateKeeper\Dummy as DummyGateKeeper;
use DecodeLabs\Disciple\Profile;
use DecodeLabs\Kingdom\Service;
use DecodeLabs\Kingdom\ServiceTrait;

class Disciple implements Service
{
    use ServiceTrait;

    public bool $loggedIn { get => $this->adapter->loggedIn; }
    public ?string $identity { get => $this->adapter->identity; }
    public Profile $profile { get => $this->adapter->profile; }
    public Client $client { get => $this->adapter->client; }

    public ?string $id { get => $this->adapter->profile->id; }

    public string $activeId {
        get {
            if (!$this->adapter->loggedIn) {
                throw Exceptional::Runtime(
                    message: 'User is not logged in'
                );
            }

            $id = $this->adapter->profile->id;

            if ($id === null) {
                throw Exceptional::Runtime(
                    message: 'User does not have an ID'
                );
            }

            return (string)$id;
        }
    }

    public ?string $email { get => $this->adapter->profile->email; }
    public ?string $fullName { get => $this->adapter->profile->fullName; }
    public ?string $firstName { get => $this->adapter->profile->firstName; }
    public ?string $surname { get => $this->adapter->profile->surname; }
    public ?string $nickName { get => $this->adapter->profile->nickName; }
    public ?DateTime $registrationDate { get => $this->adapter->profile->registrationDate; }
    public ?DateTime $lastLoginDate { get => $this->adapter->profile->lastLoginDate; }
    public ?string $language { get => $this->adapter->profile->language; }
    public ?string $country { get => $this->adapter->profile->country; }
    public ?string $timeZone { get => $this->adapter->profile->timeZone; }

    /**
     * @var list<string>
     */
    public array $signifiers { get => $this->adapter->profile->signifiers; }

    public Ip $ip { get => $this->adapter->client->ip; }
    public string $ipString { get => $this->adapter->client->ipString; }
    public ?string $agent { get => $this->adapter->client->agent; }



    public Adapter $adapter;

    public ?GateKeeper $gateKeeper {
        get {
            if (isset($this->gateKeeper)) {
                return $this->gateKeeper;
            }

            if ($this->adapter instanceof GateKeeperAdapter) {
                return $this->gateKeeper = $this->adapter->gateKeeper;
            }

            return $this->gateKeeper = new DummyGateKeeper();
        }
    }


    public function __construct(
        ?Adapter $adapter = null
    ) {
        $this->adapter = $adapter ?? new Dummy();
    }

    public function isA(
        string ...$signifiers
    ): bool {
        return $this->adapter->isA(...$signifiers);
    }
}
