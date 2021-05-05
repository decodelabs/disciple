# Disciple

[![PHP from Packagist](https://img.shields.io/packagist/php-v/decodelabs/disciple?style=flat-square)](https://packagist.org/packages/decodelabs/disciple)
[![Latest Version](https://img.shields.io/packagist/v/decodelabs/disciple.svg?style=flat-square)](https://packagist.org/packages/decodelabs/disciple)
[![Total Downloads](https://img.shields.io/packagist/dt/decodelabs/disciple.svg?style=flat-square)](https://packagist.org/packages/decodelabs/disciple)
[![Build Status](https://img.shields.io/travis/com/decodelabs/disciple/main.svg?style=flat-square)](https://travis-ci.com/decodelabs/disciple)
[![PHPStan](https://img.shields.io/badge/PHPStan-enabled-44CC11.svg?longCache=true&style=flat-square)](https://github.com/phpstan/phpstan)
[![License](https://img.shields.io/packagist/l/decodelabs/disciple?style=flat-square)](https://packagist.org/packages/decodelabs/disciple)

Take control of your users

## Installation

Install via Composer:

```bash
composer require decodelabs/disciple
```

### PHP version

_Please note, the final v1 releases of all Decode Labs libraries will target **PHP8** or above._

Current support for earlier versions of PHP will be phased out in the coming months.


## Usage

### Importing

Disciple uses [Veneer](https://github.com/decodelabs/veneer) to provide a unified frontage under <code>DecodeLabs\Disciple</code>.
You can access all the primary functionality via this static frontage without compromising testing and dependency injection.


### Implementation

Disciple currently offers a set of simple interfaces that allows third party code to define reliable entry points to user state and data.

```php
namespace DecodeLabs\Disciple;

interface Adapter
{
    public function isLoggedIn(): bool;

    public function getIdentity(): ?string;
    public function getProfile(): Profile;

    public function isA(string ...$signifiers): bool;
}
```

An implementation of Disciple revolves around an Adapter - this acts as the primary mediator between the Disciple Veneer frontage and your system's user management infrastructure.

Your adapter should be registered during your app's bootstrap process:

```php
use DecodeLabs\Disciple;
use My\App\DiscipleAdapter;

Disciple::setAdapter(new DiscipleAdapter(
    $myUserManager
));
```

Then at any future point, queries can be made against the current user:

```php
use DecodeLabs\Disciple;

if(Disciple::isLoggedIn()) {
    echo 'Yay, you\'re logged in';
} else {
    echo 'Boo, nobody loves me';
}
```


### Profile

A registered Adapter should be able to provide an instance of a <code>Profile</code>, representing core data about the current user, such as name, email address, locale, etc.

```php
namespace DecodeLabs\Disciple;

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

    public function getSignifiers(): array;
}
```

The Veneer frontage can interface directly with this profile information, allowing quick access of user data:

```php
use DecodeLabs\Disciple;

if(Disciple::isLoggedIn()) {
    echo 'Hello '.Disciple::getFullName();
} else {
    echo 'You should probably log in first';
}
```


### Signifiers

The Disciple interfaces define the concept of <code>signifiers</code> - string keys that users can be categorised and identified by.

It is the responsibility of the Adapter implementation to define _how_ signifiers are stored and distributed, however the definition of this interface allows for a powerful, quick access mechanism for high level structures in your application.

```php
if(Disciple::isA('admin')) {
    echo 'You can see the fun stuff';
} else {
    echo 'You should go home now';
}
```


## Licensing
Disciple is licensed under the MIT License. See [LICENSE](./LICENSE) for the full license text.
