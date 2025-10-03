[![CI](https://github.com/Neuron-PHP/events/actions/workflows/ci.yml/badge.svg)](https://github.com/Neuron-PHP/events/actions)
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
    public function event($event): void;
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

    public function event($event): void
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

## Usage Examples

### E-commerce Order Processing

```php
// Events
class OrderPlacedEvent implements IEvent
{
    public function __construct(
        public readonly Order $order
    ) {}
}

class PaymentProcessedEvent implements IEvent
{
    public function __construct(
        public readonly int $orderId,
        public readonly string $transactionId,
        public readonly float $amount
    ) {}
}

// Listeners
class InventoryReductionListener implements IListener
{
    public function event($event): void
    {
        if ($event instanceof OrderPlacedEvent) {
            foreach ($event->order->items as $item) {
                Inventory::reduce($item->sku, $item->quantity);
            }
        }
    }
}

class EmailConfirmationListener implements IListener
{
    public function event($event): void
    {
        if ($event instanceof PaymentProcessedEvent) {
            $order = Order::find($event->orderId);
            EmailService::sendOrderConfirmation(
                $order->customerEmail,
                $order,
                $event->transactionId
            );
        }
    }
}

// Setup
$emitter = new Emitter();
$emitter->registerBroadcaster(new Generic());

$emitter->addListener(OrderPlacedEvent::class, new InventoryReductionListener());
$emitter->addListener(PaymentProcessedEvent::class, new EmailConfirmationListener());

// Process order
$order = new Order(...);
$emitter->emit(new OrderPlacedEvent($order));

// Process payment
$emitter->emit(new PaymentProcessedEvent(
    $order->id,
    'txn_123456',
    $order->total
));
```

### User Activity Tracking

```php
class UserActivityListener implements IListener
{
    private array $trackedEvents = [
        UserLoginEvent::class,
        UserLogoutEvent::class,
        PageViewEvent::class,
        FeatureUsedEvent::class
    ];

    public function event($event): void
    {
        if (in_array(get_class($event), $this->trackedEvents)) {
            ActivityLog::record([
                'user_id' => $event->userId ?? null,
                'event_type' => get_class($event),
                'event_data' => serialize($event),
                'ip_address' => $_SERVER['REMOTE_ADDR'] ?? null,
                'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? null,
                'timestamp' => time()
            ]);
        }
    }
}
```

## Advanced Features

### Custom Broadcasters

Create custom broadcasters for specific needs:

```php
use Neuron\Events\Broadcasters\Base;

class QueueBroadcaster extends Base
{
    private QueueManager $queue;

    public function __construct(QueueManager $queue)
    {
        $this->queue = $queue;
    }

    public function broadcast($event): void
    {
        $eventName = get_class($event);

        if (isset($this->_Listeners[$eventName])) {
            // Queue event for async processing
            $this->queue->push('events', [
                'event_name' => $eventName,
                'event_data' => serialize($event),
                'listeners' => array_map(
                    fn($l) => get_class($l),
                    $this->_Listeners[$eventName]
                )
            ]);
        }
    }
}
```

### Conditional Listeners

```php
class ConditionalListener implements IListener
{
    private IListener $innerListener;
    private callable $condition;

    public function __construct(IListener $listener, callable $condition)
    {
        $this->innerListener = $listener;
        $this->condition = $condition;
    }

    public function event($event): void
    {
        if (($this->condition)($event)) {
            $this->innerListener->event($event);
        }
    }
}

// Usage
$emitter->addListener(
    OrderPlacedEvent::class,
    new ConditionalListener(
        new HighValueOrderListener(),
        fn($event) => $event->order->total > 1000
    )
);
```

### Event Filtering

```php
class FilteredBroadcaster extends Base
{
    private array $filters = [];

    public function addFilter(string $eventName, callable $filter): void
    {
        $this->filters[$eventName][] = $filter;
    }

    public function broadcast($event): void
    {
        $eventName = get_class($event);

        // Apply filters
        if (isset($this->filters[$eventName])) {
            foreach ($this->filters[$eventName] as $filter) {
                if (!$filter($event)) {
                    return; // Skip broadcasting
                }
            }
        }

        parent::broadcast($event);
    }
}
```

## Broadcasting Strategies

### Synchronous Broadcasting

Default strategy where events are processed immediately in the same process.

```php
$broadcaster = new Generic();
// Events processed immediately when emitted
```

### Asynchronous Broadcasting

Process events in background jobs or separate processes.

```php
class AsyncBroadcaster extends Base
{
    public function broadcast($event): void
    {
        // Queue for later processing
        Queue::push(ProcessEventJob::class, [
            'event' => serialize($event),
            'listeners' => $this->_Listeners[get_class($event)] ?? []
        ]);
    }
}
```

### Buffered Broadcasting

Collect events and process them in batches.

```php
class BufferedBroadcaster extends Base
{
    private array $buffer = [];
    private int $bufferSize = 100;

    public function broadcast($event): void
    {
        $this->buffer[] = $event;

        if (count($this->buffer) >= $this->bufferSize) {
            $this->flush();
        }
    }

    public function flush(): void
    {
        foreach ($this->buffer as $event) {
            parent::broadcast($event);
        }
        $this->buffer = [];
    }
}
```

## Integration

### With Neuron Application

```php
use Neuron\Application\Base;
use Neuron\Events\Emitter;
use Neuron\Events\Broadcasters\Generic;

class MyApplication extends Base
{
    private Emitter $emitter;

    protected function onStart(): bool
    {
        // Initialize event system
        $this->emitter = new Emitter();
        $this->emitter->registerBroadcaster(new Generic());

        // Load event listeners from configuration
        $this->loadEventListeners();

        return parent::onStart();
    }

    private function loadEventListeners(): void
    {
        $config = $this->getSetting('events', 'listeners');

        foreach ($config as $eventName => $listeners) {
            foreach ($listeners as $listenerClass) {
                $this->emitter->addListener(
                    $eventName,
                    new $listenerClass()
                );
            }
        }
    }
}
```

### With Dependency Injection

```php
// Service provider
class EventServiceProvider
{
    public function register(Container $container): void
    {
        $container->singleton(Emitter::class, function() {
            $emitter = new Emitter();
            $emitter->registerBroadcaster(new Generic());
            return $emitter;
        });
    }

    public function boot(Container $container): void
    {
        $emitter = $container->get(Emitter::class);

        // Register application event listeners
        $emitter->addListener(
            UserRegisteredEvent::class,
            $container->get(WelcomeEmailListener::class)
        );
    }
}
```

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

### Test Helpers

```php
class EventRecorder implements IListener
{
    private array $events = [];

    public function event($event): void
    {
        $this->events[] = $event;
    }

    public function getEvents(): array
    {
        return $this->events;
    }

    public function hasEvent(string $eventClass): bool
    {
        foreach ($this->events as $event) {
            if ($event instanceof $eventClass) {
                return true;
            }
        }
        return false;
    }

    public function clear(): void
    {
        $this->events = [];
    }
}

// Usage in tests
$recorder = new EventRecorder();
$emitter->addListener(OrderPlacedEvent::class, $recorder);

// ... execute code that should emit event

$this->assertTrue($recorder->hasEvent(OrderPlacedEvent::class));
```

## Best Practices

### Event Design

```php
// Good: Immutable event with clear data
class InvoiceCreatedEvent implements IEvent
{
    public function __construct(
        public readonly int $invoiceId,
        public readonly int $customerId,
        public readonly float $amount,
        public readonly DateTime $createdAt
    ) {}
}

// Avoid: Mutable event with unclear purpose
class SomethingHappenedEvent implements IEvent
{
    public $data;
    public $type;
}
```

### Listener Organization

```php
// Single responsibility listeners
class SendInvoiceEmailListener implements IListener
{
    public function event($event): void
    {
        // Only handles email sending
        if ($event instanceof InvoiceCreatedEvent) {
            $this->emailService->sendInvoice($event->invoiceId);
        }
    }
}

// Avoid: Multi-purpose listener
class DoEverythingListener implements IListener
{
    public function event($event): void
    {
        // Too many responsibilities
        $this->sendEmail($event);
        $this->updateDatabase($event);
        $this->callWebhook($event);
        $this->logToFile($event);
    }
}
```

### Error Handling

```php
class SafeListener implements IListener
{
    private IListener $innerListener;
    private ILogger $logger;

    public function __construct(IListener $listener, ILogger $logger)
    {
        $this->innerListener = $listener;
        $this->logger = $logger;
    }

    public function event($event): void
    {
        try {
            $this->innerListener->event($event);
        } catch (\Exception $e) {
            $this->logger->error('Event listener failed', [
                'listener' => get_class($this->innerListener),
                'event' => get_class($event),
                'error' => $e->getMessage()
            ]);

            // Optionally re-throw or handle based on criticality
            if ($this->isCritical($event)) {
                throw $e;
            }
        }
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