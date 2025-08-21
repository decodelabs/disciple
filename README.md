# Disciple

[![PHP from Packagist](https://img.shields.io/packagist/php-v/decodelabs/disciple?style=flat)](https://packagist.org/packages/decodelabs/disciple)
[![Latest Version](https://img.shields.io/packagist/v/decodelabs/disciple.svg?style=flat)](https://packagist.org/packages/decodelabs/disciple)
[![Total Downloads](https://img.shields.io/packagist/dt/decodelabs/disciple.svg?style=flat)](https://packagist.org/packages/decodelabs/disciple)
[![GitHub Workflow Status](https://img.shields.io/github/actions/workflow/status/decodelabs/disciple/integrate.yml?branch=develop)](https://github.com/decodelabs/disciple/actions/workflows/integrate.yml)
[![PHPStan](https://img.shields.io/badge/PHPStan-enabled-44CC11.svg?longCache=true&style=flat)](https://github.com/phpstan/phpstan)
[![License](https://img.shields.io/packagist/l/decodelabs/disciple?style=flat)](https://packagist.org/packages/decodelabs/disciple)

### Take control of your users

Disciple offers a set of simple interfaces that allows third party code to define reliable entry points to user state and data.

---

## Installation

Install via Composer:

```bash
composer require decodelabs/disciple
```

## Usage

### Implementation

An implementation of Disciple revolves around an Adapter - this acts as the primary mediator between the Disciple service and your system's user management infrastructure.

```php
namespace DecodeLabs\Disciple;

interface Adapter
{
    public ?string $identity { get; }
    public Profile $profile { get; }
    public Client $client { get; }

    public bool $loggedIn { get; }

    public function isA(
        string ...$signifiers
    ): bool;
}
```

Your adapter should be supplied during instantiation of the Disciple service. This is typically done in your app's bootstrap process via your service container:

```php
use DecodeLabs\Disciple;
use DecodeLabs\Disciple\Adapter;
use DecodeLabs\Monarch;
use My\App\DiscipleAdapter;

Monarch::getKingdom()->container->set(
    Adapter::class,
    new DiscipleAdapter($myUserManager)
);

$disciple = Monarch::getService(Disciple::class);
```

Then at any future point, queries can be made against the current user:

```php
if($disciple->loggedIn) {
    echo 'Yay, you\'re logged in';
} else {
    echo 'Boo, nobody loves me';
}
```


### Profile

A registered Adapter should be able to provide an instance of a <code>Profile</code>, representing core data about the current user, such as name, email address, locale, etc.

```php
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
```

The service can interface directly with this profile information, allowing quick access of user data:

```php
if($disciple->loggedIn) {
    echo 'Hello ' . $disciple->fullName;
} else {
    echo 'You should probably log in first';
}
```


### Client

An Adapter should also be able to provide a Client object which can report details of how a user is interfacing with the system.

Currently, that entails the following, but with more to follow in future versions:

```php
interface Client
{
    public string $protocol { get; }
    public Ip $ip { get; }
    public string $ipString { get; }
    public ?string $agent { get; }
}
```


### Signifiers

The Disciple interfaces define the concept of <code>signifiers</code> - string keys that users can be categorised and identified by.

It is the responsibility of the Adapter implementation to define _how_ signifiers are stored and distributed, however the definition of this interface allows for a powerful, quick access mechanism for high level structures in your application.

```php
if($disciple->isA('admin')) {
    echo 'You can see the fun stuff';
} else {
    echo 'You should go home now';
}
```



## Licensing
Disciple is licensed under the MIT License. See [LICENSE](./LICENSE) for the full license text.
