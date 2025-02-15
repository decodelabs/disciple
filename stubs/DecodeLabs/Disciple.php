<?php
/**
 * This is a stub file for IDE compatibility only.
 * It should not be included in your projects.
 */
namespace DecodeLabs;

use DecodeLabs\Veneer\Proxy as Proxy;
use DecodeLabs\Veneer\ProxyTrait as ProxyTrait;
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

    public const Veneer = 'DecodeLabs\\Disciple';
    public const VeneerTarget = Inst::class;

    protected static Inst $_veneerInstance;

    public static function setAdapter(Ref0 $adapter): Inst {
        return static::$_veneerInstance->setAdapter(...func_get_args());
    }
    public static function getAdapter(): Ref0 {
        return static::$_veneerInstance->getAdapter();
    }
    public static function hasAdapter(): bool {
        return static::$_veneerInstance->hasAdapter();
    }
    public static function isLoggedIn(): bool {
        return static::$_veneerInstance->isLoggedIn();
    }
    public static function getIdentity(): ?string {
        return static::$_veneerInstance->getIdentity();
    }
    public static function getProfile(): Ref1 {
        return static::$_veneerInstance->getProfile();
    }
    public static function getClient(): Ref2 {
        return static::$_veneerInstance->getClient();
    }
    public static function getId(): ?string {
        return static::$_veneerInstance->getId();
    }
    public static function getActiveId(): string {
        return static::$_veneerInstance->getActiveId();
    }
    public static function getEmail(): ?string {
        return static::$_veneerInstance->getEmail();
    }
    public static function getFullName(): ?string {
        return static::$_veneerInstance->getFullName();
    }
    public static function getFirstName(): ?string {
        return static::$_veneerInstance->getFirstName();
    }
    public static function getSurname(): ?string {
        return static::$_veneerInstance->getSurname();
    }
    public static function getNickName(): ?string {
        return static::$_veneerInstance->getNickName();
    }
    public static function getRegistrationDate(): ?Ref3 {
        return static::$_veneerInstance->getRegistrationDate();
    }
    public static function getLastLoginDate(): ?Ref3 {
        return static::$_veneerInstance->getLastLoginDate();
    }
    public static function getLanguage(): ?string {
        return static::$_veneerInstance->getLanguage();
    }
    public static function getCountry(): ?string {
        return static::$_veneerInstance->getCountry();
    }
    public static function getTimeZone(): ?string {
        return static::$_veneerInstance->getTimeZone();
    }
    public static function getSignifiers(): array {
        return static::$_veneerInstance->getSignifiers();
    }
    public static function isA(string ...$signifiers): bool {
        return static::$_veneerInstance->isA(...func_get_args());
    }
    public static function getIp(): Ref4 {
        return static::$_veneerInstance->getIp();
    }
    public static function getIpString(): string {
        return static::$_veneerInstance->getIpString();
    }
    public static function getAgent(): ?string {
        return static::$_veneerInstance->getAgent();
    }
    public static function getGateKeeper(): Ref5 {
        return static::$_veneerInstance->getGateKeeper();
    }
};
