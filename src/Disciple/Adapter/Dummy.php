<?php

/**
 * @package Disciple
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\Disciple\Adapter;

use DecodeLabs\Disciple\Adapter;
use DecodeLabs\Disciple\Client;
use DecodeLabs\Disciple\Client\Generic as GenericClient;
use DecodeLabs\Disciple\Profile;
use DecodeLabs\Disciple\Profile\Generic as GenericProfile;
use DecodeLabs\Monarch;

class Dummy implements Adapter
{
    public ?string $identity = null;

    public Profile $profile {
        get {
            return new GenericProfile(null);
        }
    }

    public Client $client {
        get {
            return new GenericClient(
                protocol: lcfirst(Monarch::getKingdom()->runtime->mode->name),
                ip: '0.0.0.0',
                agent: null
            );
        }
    }


    public bool $loggedIn { get => false; }

    public function isA(
        string ...$signifiers
    ): bool {
        return false;
    }
}
