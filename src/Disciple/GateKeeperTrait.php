<?php

/**
 * @package Disciple
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\Disciple;

use DateTime;
use DecodeLabs\Disciple\GateKeeper\Attempt;
use DecodeLabs\Glitch;
use Throwable;

trait GateKeeperTrait
{
    /**
     * @var Adapter
     */
    protected $adapter;


    /**
     * Init with adapter
     */
    public function __construct(Adapter $adapter)
    {
        $this->adapter = $adapter;
    }


    /**
     * Approve login attempt
     */
    final public function approveLogin(
        string $identity,
        ?callable $failHandler = null
    ): bool {
        try {
            return $this->rawApproveLogin($identity, $failHandler);
        } catch (Throwable $e) {
            Glitch::logException($e);
            return true;
        }
    }


    /**
     * Approve login attempt without error catching
     */
    private function rawApproveLogin(
        string $identity,
        ?callable $failHandler = null
    ): bool {
        $identity = $this->prepareIdentity($identity);

        if (null === ($index = $this->getIndexedAttempts($identity))) {
            return true;
        }

        $ip = $this->adapter->getClient()->getIpString();
        $count = count($index[$ip] ?? []);
        $threshold = $this->getLoginThreshold();

        if ($count < $threshold) {
            return true;
        }

        $score = $count + $this->getScoreOffset($index, $ip);
        $over = $score - $threshold + 1;
        $minutes = min($over * $over / 3, $this->getMaxWaitMinutes());
        $lastAttempt = current($index[$ip]) ?? null;

        if (!$lastAttempt) {
            return true;
        }

        $now = new DateTime('now');
        $diffMins = ($now->getTimestamp() - $lastAttempt->getTimestamp()) / 60;

        if ($diffMins > $minutes) {
            return true;
        }

        $minutes -= $diffMins;

        if ($failHandler !== null) {
            $target = clone $now;
            $target->modify('+' . ($minutes * 60) . ' seconds');
            $interval = $now->diff($target);
            $failHandler($interval);
        }

        return false;
    }


    /**
     * Report login attempt
     */
    final public function reportLogin(
        string $identity,
        bool $success
    ): void {
        try {
            $client = $this->adapter->getClient();

            $this->storeAttempt(
                $this->prepareIdentity($identity),
                $client->getIpString(),
                $client->getAgent(),
                $success
            );
        } catch (Throwable $e) {
            Glitch::logException($e);
        }
    }


    /**
     * Report login without error catching
     */
    abstract protected function storeAttempt(
        string $identity,
        string $ip,
        string $agent,
        bool $success
    ): void;



    /**
     * Get login threshold
     */
    protected function getLoginThreshold(): int
    {
        return 8;
    }

    /**
     * Get attempt threshold date
     */
    protected function getAttemptThresholdDate(): DateTime
    {
        return new DateTime('-1 hour');
    }


    /**
     * Get multi IP delta
     */
    protected function getMultiIpDelta(): float
    {
        return 1 / 2.5;
    }


    /**
     * Get max timeout minutes
     */
    protected function getMaxWaitMinutes(): float
    {
        return 15.0;
    }


    /**
     * Prepare identity string
     */
    protected function prepareIdentity(string $identity): string
    {
        return trim($identity);
    }


    /**
     * Get indexed list of attempts
     *
     * @return array<string, array<Attempt>>|null
     */
    private function getIndexedAttempts(string $identity): ?array
    {
        $attempts = $this->fetchAttempts($identity, $this->getAttemptThresholdDate());
        $count = count($attempts);

        if ($count < $this->getLoginThreshold()) {
            return null;
        }

        $index = $authed = [];

        foreach ($attempts as $attempt) {
            $ip = $attempt->getIp();

            if ($attempt->wasSuccessful()) {
                $authed[$ip] = true;
            }

            if ($authed[$ip] ?? false) {
                continue;
            }

            $index[$ip][] = $attempt->getDate();
        }

        return $index;
    }


    /**
     * Fetch stored list of login attempts
     *
     * @return array<Attempt>
     */
    abstract protected function fetchAttempts(
        string $identity,
        DateTime $since
    ): array;


    /**
     * Get score offset
     *
     * @param array<string, array<mixed>> $index
     */
    private function getScoreOffset(
        array $index,
        string $ip
    ): float {
        $offset = 0;

        foreach ($index as $ipKey => $attempts) {
            if ($ipKey == $ip) {
                continue;
            }

            $offset += count($attempts);
        }

        $offset /= $this->getMultiIpDelta();
        return $offset;
    }
}
