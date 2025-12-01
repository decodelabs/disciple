# Disciple — Package Specification

> **Cluster:** `data`
> **Language:** `php`
> **Milestone:** `m4`
> **Repo:** `https://github.com/decodelabs/disciple`
> **Role:** User data access

This document describes the purpose, contracts, and design of **Disciple** within the Decode Labs ecosystem.

It is aimed at:

- Developers **using** Disciple in their own applications or libraries.
- Contributors **maintaining or extending** Disciple.
- Tools and AI assistants that need to reason about its behaviour.

---

## 1. Overview

### 1.1 Purpose

Disciple offers a set of simple interfaces that allows third party code to define reliable entry points to user state and data. It provides a unified interface for accessing user information, authentication status, profile data, client information, and signifiers (roles/permissions). The package includes an adapter pattern for integrating with existing user management systems, a gatekeeper system for login attempt tracking and rate limiting, and generic implementations for testing and development.

### 1.2 Non-Goals

Disciple does **not**:

- Provide authentication logic — it accesses user state, doesn't authenticate
- Handle password management — it's a data access layer
- Provide session management — it reads user state from adapters
- Handle user registration — it accesses existing user data
- Provide authorization logic — it provides signifiers, doesn't enforce permissions
- Handle user storage — adapters handle storage
- Provide UI components — it's a backend data access library
- Handle OAuth or SSO — adapters can implement these

---

## 2. Role in the Ecosystem

### 2.1 Cluster & Positioning

- **Cluster:** `data` (see Chorus taxonomy)
- Disciple is a data package that provides user data access interfaces for the Decode Labs ecosystem. It sits in the data cluster alongside other data access utilities. It depends on Compass, Exceptional, Kingdom, and Monarch. It's used throughout the ecosystem for accessing current user information, checking authentication status, and querying user signifiers (roles/permissions).

### 2.2 Typical Usage Contexts

Typical places Disciple appears:

- Authentication checks
- User profile access
- Permission/role checking
- Client information access (IP, user agent)
- Login attempt tracking
- Rate limiting for login attempts
- User data display
- Localization based on user preferences

Disciple is intended to be used whenever code needs to access information about the current user in a standardized way, regardless of the underlying user management system.

---

## 3. Public Surface

> This section focuses on the conceptual API, not every symbol.

### 3.1 Key Types

The primary public types are:

- `DecodeLabs\Disciple`
  Main service class implementing `Kingdom\Service`. Provides unified access to user data through an adapter pattern.

- `DecodeLabs\Disciple\Adapter`
  Interface for user data adapters. Defines contract for accessing user identity, profile, client, login status, and signifiers.

- `DecodeLabs\Disciple\Adapter\Dummy`
  Dummy adapter implementation for testing and development. Returns empty/default values.

- `DecodeLabs\Disciple\Adapter\GateKeeper`
  Interface extending `Adapter` for adapters that provide gatekeeper functionality.

- `DecodeLabs\Disciple\Profile`
  Interface for user profile data. Defines properties for ID, email, name fields, dates, locale, and signifiers.

- `DecodeLabs\Disciple\Profile\Generic`
  Generic profile implementation. Provides basic profile data storage and name parsing logic.

- `DecodeLabs\Disciple\Client`
  Interface for client information. Defines properties for protocol, IP address, and user agent.

- `DecodeLabs\Disciple\Client\Generic`
  Generic client implementation. Provides basic client data storage.

- `DecodeLabs\Disciple\GateKeeper`
  Interface for login attempt tracking and rate limiting. Defines methods for approving logins and reporting attempts.

- `DecodeLabs\Disciple\GateKeeper\Dummy`
  Dummy gatekeeper implementation that always approves logins.

- `DecodeLabs\Disciple\GateKeeper\Attempt`
  Value object representing a login attempt with date, IP, and success status.

- `DecodeLabs\Disciple\GateKeeperTrait`
  Trait providing gatekeeper implementation logic. Handles login attempt tracking, rate limiting, and timeout calculation.

### 3.2 Main Entry Points

The main usage pattern is through the `Disciple` service:

```php
use DecodeLabs\Disciple;

$disciple = new Disciple($adapter);

if ($disciple->loggedIn) {
    echo $disciple->fullName;
}

if ($disciple->isA('admin')) {
    // Admin access
}
```

---

## 4. Dependencies

### 4.1 Decode Labs

- `decodelabs/compass` (required)
  Used for IP address handling (`Ip` type) in client and gatekeeper functionality.

- `decodelabs/exceptional` (required)
  Used for exception handling when user operations fail (e.g., accessing activeId when not logged in).

- `decodelabs/kingdom` (required)
  Used for service interface (`Service`, `ServiceTrait`).

- `decodelabs/monarch` (required)
  Used for logging exceptions in gatekeeper trait and accessing runtime mode in dummy adapter.

### 4.2 External

- None

### 4.3 Optional Integrations

- None

---

## 5. Behaviour & Contracts

### 5.1 Invariants

- Adapter must always provide a `Profile` instance (never null)
- Adapter must always provide a `Client` instance (never null)
- `Profile` signifiers are always a list of strings
- `Client` IP is always a valid `Ip` instance
- `Client` protocol is always a string
- `activeId` throws exception if user is not logged in or has no ID
- Gatekeeper always returns boolean for `approveLogin()`
- Gatekeeper attempts are tracked per identity and IP
- Login attempts older than threshold are ignored
- Successful login attempts reset rate limiting for that IP

### 5.2 Input & Output Contracts

**Disciple Service Operations:**
- `__construct(?Adapter $adapter)` — Creates service with adapter (defaults to Dummy)
- `isA(string ...$signifiers): bool` — Checks if user has any of the signifiers
- Properties:
  - `bool $loggedIn` — User login status
  - `?string $identity` — User identity string
  - `Profile $profile` — User profile
  - `Client $client` — Client information
  - `?string $id` — User ID (from profile)
  - `string $activeId` — User ID (throws if not logged in or no ID)
  - `?string $email` — User email
  - `?string $fullName` — User full name
  - `?string $firstName` — User first name
  - `?string $surname` — User surname
  - `?string $nickName` — User nickname
  - `?DateTime $registrationDate` — Registration date
  - `?DateTime $lastLoginDate` — Last login date
  - `?string $language` — User language
  - `?string $country` — User country
  - `?string $timeZone` — User timezone
  - `array $signifiers` — User signifiers (roles/permissions)
  - `Ip $ip` — Client IP address
  - `string $ipString` — Client IP as string
  - `?string $agent` — User agent string
  - `Adapter $adapter` — Adapter instance
  - `?GateKeeper $gateKeeper` — Gatekeeper instance (lazy-loaded)

**Adapter Operations:**
- `?string $identity { get; }` — User identity string
- `Profile $profile { get; }` — User profile
- `Client $client { get; }` — Client information
- `bool $loggedIn { get; }` — Login status
- `isA(string ...$signifiers): bool` — Checks if user has any signifiers

**Profile Operations:**
- `?string $id { get; }` — User ID
- `?string $email { get; }` — Email address
- `?string $fullName { get; }` — Full name
- `?string $firstName { get; }` — First name
- `?string $surname { get; }` — Surname
- `?string $nickName { get; }` — Nickname
- `?DateTime $registrationDate { get; }` — Registration date
- `?DateTime $lastLoginDate { get; }` — Last login date
- `?string $language { get; }` — Language code
- `?string $country { get; }` — Country code
- `?string $timeZone { get; }` — Timezone
- `array $signifiers { get; }` — Signifiers list

**Client Operations:**
- `string $protocol { get; }` — Protocol (e.g., 'http', 'cli')
- `Ip $ip { get; }` — IP address object
- `string $ipString { get; }` — IP address as string
- `?string $agent { get; }` — User agent string

**GateKeeper Operations:**
- `approveLogin(string $identity, ?callable $failHandler): bool` — Approves login attempt (returns false if rate limited)
- `reportLogin(string $identity, bool $success): void` — Reports login attempt result

**GateKeeper Adapter Operations:**
- `GateKeeper $gateKeeper { get; }` — Gatekeeper instance

**Attempt Operations:**
- `DateTime $date { get; }` — Attempt date
- `Ip $ip { get; }` — Attempt IP
- `string $ipString { get; }` — Attempt IP as string
- `bool $success { get; }` — Success status
- `wasSuccessful(): bool` — Checks if attempt was successful

**GateKeeperTrait Operations:**
- `__construct(Adapter $adapter)` — Initializes with adapter
- `approveLogin(string $identity, ?callable $failHandler): bool` — Approves login (with error handling)
- `reportLogin(string $identity, bool $success): void` — Reports login (with error handling)
- `protected storeAttempt(string $identity, string $ip, ?string $agent, bool $success): void` — Abstract method to store attempt
- `protected fetchAttempts(string $identity, DateTime $since): array` — Abstract method to fetch attempts
- `protected getLoginThreshold(): int` — Gets login threshold (default: 8)
- `protected getAttemptThresholdDate(): DateTime` — Gets attempt threshold date (default: -1 hour)
- `protected getMultiIpDelta(): float` — Gets multi-IP delta (default: 1/2.5)
- `protected getMaxWaitMinutes(): float` — Gets max wait minutes (default: 15.0)
- `protected prepareIdentity(string $identity): string` — Prepares identity string (default: trim)

### 5.3 Adapter Pattern

Disciple uses an adapter pattern to integrate with existing user management systems:
- Applications implement the `Adapter` interface
- Adapter provides user data from their system
- Disciple service delegates all operations to adapter
- Allows multiple user systems to work with Disciple

### 5.4 Signifiers

Signifiers are string keys that categorize users:
- Used for roles, permissions, groups, etc.
- Stored in profile's `signifiers` array
- Checked via `isA()` method
- Adapter implementation defines how signifiers are stored/retrieved
- `isA()` returns true if user has any of the provided signifiers

### 5.5 GateKeeper Rate Limiting

GateKeeper provides login attempt rate limiting:
- Tracks attempts per identity and IP
- Default threshold: 8 attempts per hour
- Calculates timeout based on attempt count
- Timeout formula: `min(over^2 / 3, maxWaitMinutes)`
- Multi-IP attempts increase score
- Successful logins reset rate limiting for that IP
- Failed attempts are tracked for rate limiting

### 5.6 Profile Name Parsing

Generic profile implementation parses names:
- Extracts first name by splitting on spaces/dots
- Skips common titles (Mr, Mrs, Miss, Ms, Mx, Master, Maid, Madam, Dr)
- Extracts surname as last part after splitting
- Handles commas, dots, and hyphens in names

### 5.7 Client Information

Client information includes:
- Protocol (http, https, cli, etc.)
- IP address (as `Ip` object and string)
- User agent string (optional)

### 5.8 Dummy Implementations

Dummy implementations provided for testing:
- `Adapter\Dummy` — Returns empty/default values, always not logged in
- `GateKeeper\Dummy` — Always approves logins, doesn't track attempts
- Used when no real adapter is available

---

## 6. Error Handling

- Accessing `activeId` when not logged in throws `Exceptional::Runtime`
- Accessing `activeId` when user has no ID throws `Exceptional::Runtime`
- GateKeeper operations catch exceptions and log via Monarch
- GateKeeper `approveLogin()` returns true on exceptions (fail-open)
- GateKeeper `reportLogin()` silently handles exceptions

---

## 7. Configuration & Extensibility

- Adapter implementation defines how user data is stored/retrieved
- GateKeeperTrait provides configurable thresholds:
  - Login threshold (default: 8)
  - Attempt threshold date (default: -1 hour)
  - Multi-IP delta (default: 1/2.5)
  - Max wait minutes (default: 15.0)
- Identity preparation can be overridden in GateKeeperTrait
- Abstract methods in GateKeeperTrait must be implemented for storage

---

## 8. Interactions with Other Packages

### 8.1 Compass

Disciple uses Compass for IP address handling:
- `Client` interface uses `Ip` type from Compass
- `Attempt` uses `Ip` type for tracking
- IP addresses are parsed using `Ip::parse()`

### 8.2 Exceptional

Disciple uses Exceptional for exception handling when user operations fail, providing consistent error reporting across the ecosystem.

### 8.3 Kingdom

Disciple implements Kingdom's `Service` interface, allowing it to be registered as a service in the Kingdom service container.

### 8.4 Monarch

Disciple uses Monarch for:
- Logging exceptions in GateKeeperTrait
- Accessing runtime mode in Dummy adapter for protocol detection

---

## 9. Usage Examples

### 9.1 Basic Usage

```php
use DecodeLabs\Disciple;
use DecodeLabs\Disciple\Adapter;
use My\App\DiscipleAdapter;

// Create adapter
$adapter = new DiscipleAdapter($myUserManager);

// Create service
$disciple = new Disciple($adapter);

// Check login status
if ($disciple->loggedIn) {
    echo 'Hello ' . $disciple->fullName;
} else {
    echo 'Please log in';
}
```

### 9.2 Signifier Checking

```php
use DecodeLabs\Disciple;

$disciple = new Disciple($adapter);

// Check single signifier
if ($disciple->isA('admin')) {
    // Admin access
}

// Check multiple signifiers (OR logic)
if ($disciple->isA('admin', 'moderator')) {
    // Admin or moderator access
}
```

### 9.3 Profile Access

```php
use DecodeLabs\Disciple;

$disciple = new Disciple($adapter);

if ($disciple->loggedIn) {
    echo 'ID: ' . $disciple->id;
    echo 'Email: ' . $disciple->email;
    echo 'Name: ' . $disciple->fullName;
    echo 'First: ' . $disciple->firstName;
    echo 'Surname: ' . $disciple->surname;
    echo 'Language: ' . $disciple->language;
    echo 'Timezone: ' . $disciple->timeZone;
}
```

### 9.4 Client Information

```php
use DecodeLabs\Disciple;

$disciple = new Disciple($adapter);

$ip = $disciple->ip; // Ip object
$ipString = $disciple->ipString; // String
$protocol = $disciple->client->protocol;
$agent = $disciple->agent;
```

### 9.5 GateKeeper Usage

```php
use DecodeLabs\Disciple;
use DecodeLabs\Disciple\Adapter\GateKeeper as GateKeeperAdapter;

$disciple = new Disciple($adapter);

if ($disciple->adapter instanceof GateKeeperAdapter) {
    $gateKeeper = $disciple->gateKeeper;
    
    // Check if login is allowed
    $allowed = $gateKeeper->approveLogin(
        $identity,
        function ($interval) {
            echo 'Please wait ' . $interval->format('%i minutes');
        }
    );
    
    if ($allowed) {
        // Attempt login
        $success = attemptLogin($identity, $password);
        
        // Report result
        $gateKeeper->reportLogin($identity, $success);
    }
}
```

### 9.6 Creating an Adapter

```php
use DecodeLabs\Disciple\Adapter;
use DecodeLabs\Disciple\Profile;
use DecodeLabs\Disciple\Client;
use DecodeLabs\Disciple\Profile\Generic as GenericProfile;
use DecodeLabs\Disciple\Client\Generic as GenericClient;

class MyAdapter implements Adapter
{
    public function __construct(
        private MyUserManager $userManager
    ) {}
    
    public ?string $identity {
        get {
            return $this->userManager->getCurrentIdentity();
        }
    }
    
    public Profile $profile {
        get {
            $user = $this->userManager->getCurrentUser();
            
            if ($user === null) {
                return new GenericProfile(null);
            }
            
            return new GenericProfile(
                id: $user->getId(),
                email: $user->getEmail(),
                fullName: $user->getFullName(),
                signifiers: $user->getRoles()
            );
        }
    }
    
    public Client $client {
        get {
            return new GenericClient(
                protocol: $_SERVER['REQUEST_SCHEME'] ?? 'http',
                ip: $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0',
                agent: $_SERVER['HTTP_USER_AGENT'] ?? null
            );
        }
    }
    
    public bool $loggedIn {
        get {
            return $this->userManager->isLoggedIn();
        }
    }
    
    public function isA(
        string ...$signifiers
    ): bool {
        $user = $this->userManager->getCurrentUser();
        
        if ($user === null) {
            return false;
        }
        
        $userSignifiers = $user->getRoles();
        
        foreach ($signifiers as $signifier) {
            if (in_array($signifier, $userSignifiers)) {
                return true;
            }
        }
        
        return false;
    }
}
```

### 9.7 Creating a GateKeeper

```php
use DecodeLabs\Disciple\GateKeeper;
use DecodeLabs\Disciple\GateKeeperTrait;
use DecodeLabs\Disciple\GateKeeper\Attempt;
use DecodeLabs\Disciple\Adapter;

class MyGateKeeper implements GateKeeper
{
    use GateKeeperTrait;
    
    protected function storeAttempt(
        string $identity,
        string $ip,
        ?string $agent,
        bool $success
    ): void {
        // Store attempt in database/cache
        $this->database->insert('login_attempts', [
            'identity' => $identity,
            'ip' => $ip,
            'agent' => $agent,
            'success' => $success,
            'date' => new DateTime('now')
        ]);
    }
    
    protected function fetchAttempts(
        string $identity,
        DateTime $since
    ): array {
        // Fetch attempts from database/cache
        $rows = $this->database->select('login_attempts', [
            'identity' => $identity,
            'date >=' => $since
        ]);
        
        $attempts = [];
        
        foreach ($rows as $row) {
            $attempts[] = new Attempt(
                date: $row['date'],
                ip: $row['ip'],
                success: $row['success']
            );
        }
        
        return $attempts;
    }
}
```

---

## 10. Implementation Notes (for Contributors)

### 10.1 Adapter Pattern

The adapter pattern allows Disciple to work with any user management system:
- Applications implement `Adapter` interface
- Adapter provides data from their system
- Disciple service delegates to adapter
- No assumptions about storage or authentication

### 10.2 Profile Interface

Profile interface defines standard user data:
- All properties are nullable except `signifiers` (empty array)
- `signifiers` is always a list of strings
- Generic implementation provides basic storage and name parsing

### 10.3 Client Interface

Client interface defines connection information:
- Protocol identifies connection type (http, cli, etc.)
- IP is always a Compass `Ip` object
- Agent is optional (may be null)

### 10.4 GateKeeper Rate Limiting

GateKeeper provides sophisticated rate limiting:
- Tracks attempts per identity and IP
- Calculates timeout based on attempt count
- Formula: `min(over^2 / 3, maxWaitMinutes)`
- Multi-IP attempts increase score (divided by delta)
- Successful logins reset rate limiting for that IP
- Only failed attempts count toward rate limiting

### 10.5 GateKeeperTrait

GateKeeperTrait provides implementation logic:
- Handles error catching and logging
- Provides configurable thresholds
- Implements timeout calculation
- Requires abstract methods for storage:
  - `storeAttempt()` — Store attempt
  - `fetchAttempts()` — Fetch attempts

### 10.6 Name Parsing

Generic profile name parsing:
- Splits on spaces and dots
- Skips common titles
- Extracts first name and surname
- Handles various name formats

### 10.7 Dummy Implementations

Dummy implementations for testing:
- Always return safe defaults
- Don't throw exceptions
- Useful for development and testing

---

## 11. Testing & Quality

- **Code Quality Score:** 3/5
- **README Quality Score:** 3/5
- **Documentation Score:** 0/5 (this spec)
- **Test Coverage Score:** 0/5

See `composer.json` for supported PHP versions.

---

## 12. Roadmap & Future Ideas

- Add more client information (device, browser, etc.)
- Add session management integration
- Add OAuth/SSO adapter helpers
- Improve documentation and usage examples
- Add test coverage
- Consider adding user preference storage
- Consider adding user activity tracking
- Consider adding more gatekeeper features
- Consider adding user group management

---

## 13. References

- [Compass Package](https://github.com/decodelabs/compass) — IP address handling
- [Exceptional Package](https://github.com/decodelabs/exceptional) — Exception handling
- [Kingdom Package](https://github.com/decodelabs/kingdom) — Service container
- [Monarch Package](https://github.com/decodelabs/monarch) — Logging and runtime
- [Chorus Package Index](../../../chorus/config/packages.json) — Ecosystem metadata

