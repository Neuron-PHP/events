[![CI](https://github.com/Neuron-PHP/events/actions/workflows/ci.yml/badge.svg)](https://github.com/Neuron-PHP/events/actions)
[![codecov](https://codecov.io/gh/Neuron-PHP/events/graph/badge.svg)](https://codecov.io/gh/Neuron-PHP/events)
# Neuron-PHP Events

A flexible event-driven programming component for PHP 8.4+ that provides a robust framework for managing events, listeners, and broadcasters with support for multiple broadcasting strategies.

## Table of Contents

- [Installation](#installation)
- [Core Concepts](#core-concepts)
- [Quick Start](#quick-start)
- [Components](#components)
  - [Events](#events)
  - [Emitter](#emitter)
  - [Broadcasters](#broadcasters)
  - [Listeners](#listeners)
- [Usage Examples](#usage-examples)
- [Advanced Features](#advanced-features)
- [Broadcasting Strategies](#broadcasting-strategies)
- [Integration](#integration)
- [Testing](#testing)
- [Best Practices](#best-practices)
- [More Information](#more-information)

## Installation

### Requirements

- PHP 8.4 or higher
- Composer
- Extensions: curl, json

### Install via Composer

```bash
composer require neuron-php/events
```

## Core Concepts

The Events component implements a publish-subscribe pattern with the following key concepts:

- **Events**: Objects representing significant state changes in your application
- **Emitter**: Central hub for dispatching events to broadcasters
- **Broadcasters**: Components that distribute events to listeners
- **Listeners**: Objects that respond to specific events

### Event Flow

```
Application → Event → Emitter → Broadcasters → Listeners
```

## Quick Start

### 1. Create an Event

```php
use Neuron\Events\IEvent;

class UserRegisteredEvent implements IEvent
{
    public function __construct(
        public readonly int $userId,
        public readonly string $email,
        public readonly DateTime $registeredAt
    ) {}
}
```

### 2. Create Listeners

```php
use Neuron\Events\IListener;

class SendWelcomeEmailListener implements IListener
{
    public function event($event): void
    {
        // Send welcome email to user
        $mailer = new Mailer();
        $mailer->send($event->email, 'Welcome!', 'Thanks for registering!');
    }
}

class UpdateAnalyticsListener implements IListener
{
    public function event($event): void
    {
        // Update registration analytics
        Analytics::track('user.registered', [
            'user_id' => $event->userId,
            'timestamp' => $event->registeredAt
        ]);
    }
}
```

### 3. Configure and Use

```php
use Neuron\Events\Emitter;
use Neuron\Events\Broadcasters\Generic;

// Set up the event system
$emitter = new Emitter();
$broadcaster = new Generic();
$emitter->registerBroadcaster($broadcaster);

// Register listeners
$emitter->addListener(
    UserRegisteredEvent::class,
    new SendWelcomeEmailListener()
);
$emitter->addListener(
    UserRegisteredEvent::class,
    new UpdateAnalyticsListener()
);

// Emit the event
$event = new UserRegisteredEvent(123, 'user@example.com', new DateTime());
$emitter->emit($event);
```

## Components

### Events

Events are simple objects that implement the `IEvent` interface and carry data about state changes.

#### Event Interface

```php
namespace Neuron\Events;

interface IEvent
{
    // Marker interface - no required methods
}
```

#### Event Examples

```php
// Domain event
class OrderCompletedEvent implements IEvent
{
    public function __construct(
        public readonly int $orderId,
        public readonly float $total,
        public readonly array $items
    ) {}
}

// System event
class CacheInvalidatedEvent implements IEvent
{
    public function __construct(
        public readonly string $key,
        public readonly ?string $tag = null
    ) {}
}

// Integration event
class WebhookReceivedEvent implements IEvent
{
    public function __construct(
        public readonly string $source,
        public readonly array $payload,
        public readonly array $headers
    ) {}
}
```

### Emitter

The `Emitter` class is the central coordinator for the event system, managing broadcasters and event distribution.

#### Key Methods

```php
class Emitter
{
    // Register a broadcaster
    public function registerBroadcaster(IBroadcaster $broadcaster): void;

    // Get all registered broadcasters
    public function getBroadcasters(): array;

    // Add listener to all broadcasters
    public function addListener(string $eventName, IListener $listener): bool;

    // Emit an event to all broadcasters
    public function emit(IEvent $event): void;
}
```

#### Usage

```php
$emitter = new Emitter();

// Register multiple broadcasters
$emitter->registerBroadcaster(new Generic());
$emitter->registerBroadcaster(new Log($logger));
$emitter->registerBroadcaster(new AsyncBroadcaster());

// Add listeners (applied to all broadcasters)
$emitter->addListener(OrderCompletedEvent::class, new InventoryListener());
$emitter->addListener(OrderCompletedEvent::class, new InvoiceListener());

// Emit events
$emitter->emit(new OrderCompletedEvent($orderId, $total, $items));
```

### Broadcasters

Broadcasters are responsible for distributing events to registered listeners. The component includes several built-in broadcasters.

#### Broadcaster Interface

```php
namespace Neuron\Events\Broadcasters;

interface IBroadcaster
{
    public function addListener(string $eventName, IListener $listener): bool;
    public function broadcast($event): void;
}
```

#### Generic Broadcaster

The default in-memory broadcaster for synchronous event processing.

```php
use Neuron\Events\Broadcasters\Generic;

$broadcaster = new Generic();
$broadcaster->addListener(UserLoginEvent::class, new AuditListener());
$broadcaster->broadcast(new UserLoginEvent($userId));
```

#### Log Broadcaster

Writes all event activity to a log destination for debugging and auditing.

```php
use Neuron\Events\Broadcasters\Log;
use Neuron\Log\Logger;

$logger = new Logger('events.log');
$logBroadcaster = new Log($logger);

// All events will be logged
$emitter->registerBroadcaster($logBroadcaster);
```

### Listeners

Listeners respond to events by implementing the `IListener` interface.

#### Listener Interface

```php
namespace Neuron\Events;

interface IListener
{
    public function event( IEvent $event): void;
}
```

#### Listener Examples

```php
// Database listener
class PersistEventListener implements IListener
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function event(IEvent $event): void
    {
        $stmt = $this->db->prepare(
            'INSERT INTO events (type, data, created_at) VALUES (?, ?, ?)'
        );
        $stmt->execute([
            get_class($event),
            json_encode($event),
            date('Y-m-d H:i:s')
        ]);
    }
}

// Notification listener
class SlackNotificationListener implements IListener
{
    private SlackClient $slack;

    public function __construct(SlackClient $slack)
    {
        $this->slack = $slack;
    }

    public function event($event): void
    {
        if ($event instanceof CriticalErrorEvent) {
            $this->slack->sendMessage('#alerts', [
                'text' => 'Critical error occurred!',
                'attachments' => [
                    [
                        'color' => 'danger',
                        'title' => $event->message,
                        'text' => $event->stackTrace
                    ]
                ]
            ]);
        }
    }
}
```

## Integration


## Testing

### Unit Testing Events

```php
use PHPUnit\Framework\TestCase;

class EventSystemTest extends TestCase
{
    public function testEventEmission(): void
    {
        $emitter = new Emitter();
        $broadcaster = new Generic();
        $emitter->registerBroadcaster($broadcaster);

        $listener = $this->createMock(IListener::class);
        $listener->expects($this->once())
                 ->method('event')
                 ->with($this->isInstanceOf(UserLoginEvent::class));

        $emitter->addListener(UserLoginEvent::class, $listener);
        $emitter->emit(new UserLoginEvent(123));
    }

    public function testMultipleListeners(): void
    {
        $emitter = new Emitter();
        $emitter->registerBroadcaster(new Generic());

        $called = [];

        $listener1 = new class($called) implements IListener {
            public function __construct(private array &$called) {}
            public function event($event): void {
                $this->called[] = 'listener1';
            }
        };

        $listener2 = new class($called) implements IListener {
            public function __construct(private array &$called) {}
            public function event($event): void {
                $this->called[] = 'listener2';
            }
        };

        $emitter->addListener(TestEvent::class, $listener1);
        $emitter->addListener(TestEvent::class, $listener2);
        $emitter->emit(new TestEvent());

        $this->assertEquals(['listener1', 'listener2'], $called);
    }
}
```

## More Information

- **Neuron Framework**: [neuronphp.com](http://neuronphp.com)
- **GitHub**: [github.com/neuron-php/events](https://github.com/neuron-php/events)
- **Core Component**: [github.com/neuron-php/core](https://github.com/neuron-php/core) - Contains Event singleton for application-wide events
- **Packagist**: [packagist.org/packages/neuron-php/events](https://packagist.org/packages/neuron-php/events)

## License

MIT License - see LICENSE file for details
