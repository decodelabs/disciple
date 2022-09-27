<?php
/**
 * This is a stub file for IDE compatibility only.
 * It should not be included in your projects.
 */
namespace DecodeLabs;

use DecodeLabs\Veneer\Proxy;
use DecodeLabs\Veneer\ProxyTrait;
use DecodeLabs\Disciple\Context as Inst;
use DecodeLabs\Disciple\Adapter as Ref0;
use DecodeLabs\Disciple\Profile as Ref1;
use DecodeLabs\Disciple\Client as Ref2;
use DateTime as Ref3;
use DecodeLabs\Compass\Ip as Ref4;
use DecodeLabs\Disciple\GateKeeper as Ref5;

class Disciple implements Proxy
{
    use ProxyTrait;

    const VENEER = 'DecodeLabs\Disciple';
    const VENEER_TARGET = Inst::class;

    public static Inst $instance;

    public static function setAdapter(Ref0 $adapter): Inst {
        return static::$instance->setAdapter(...func_get_args());
    }
    public static function getAdapter(): Ref0 {
        return static::$instance->getAdapter();
    }
    public static function hasAdapter(): bool {
        return static::$instance->hasAdapter();
    }
    public static function isLoggedIn(): bool {
        return static::$instance->isLoggedIn();
    }
    public static function getIdentity(): ?string {
        return static::$instance->getIdentity();
    }
    public static function getProfile(): Ref1 {
        return static::$instance->getProfile();
    }
    public static function getClient(): Ref2 {
        return static::$instance->getClient();
    }
    public static function getId(): ?string {
        return static::$instance->getId();
    }
    public static function getActiveId(): string {
        return static::$instance->getActiveId();
    }
    public static function getEmail(): ?string {
        return static::$instance->getEmail();
    }
    public static function getFullName(): ?string {
        return static::$instance->getFullName();
    }
    public static function getFirstName(): ?string {
        return static::$instance->getFirstName();
    }
    public static function getSurname(): ?string {
        return static::$instance->getSurname();
    }
    public static function getNickName(): ?string {
        return static::$instance->getNickName();
    }
    public static function getRegistrationDate(): ?Ref3 {
        return static::$instance->getRegistrationDate();
    }
    public static function getLastLoginDate(): ?Ref3 {
        return static::$instance->getLastLoginDate();
    }
    public static function getLanguage(): ?string {
        return static::$instance->getLanguage();
    }
    public static function getCountry(): ?string {
        return static::$instance->getCountry();
    }
    public static function getTimeZone(): ?string {
        return static::$instance->getTimeZone();
    }
    public static function getSignifiers(): array {
        return static::$instance->getSignifiers();
    }
    public static function isA(string ...$signifiers): bool {
        return static::$instance->isA(...func_get_args());
    }
    public static function getIp(): Ref4 {
        return static::$instance->getIp();
    }
    public static function getIpString(): string {
        return static::$instance->getIpString();
    }
    public static function getAgent(): ?string {
        return static::$instance->getAgent();
    }
    public static function getGateKeeper(): Ref5 {
        return static::$instance->getGateKeeper();
    }
};
