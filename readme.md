# About Neuron

## Terminology

### Events

Events are objects that represent specific activity taking place within a system.

### Listeners

Listeners perform actions when specific events are triggered.

### Broadcasters

Broadcasters broadcast events to specific domain. The generic broadcaster would be the most common as the events are broadcast immediately.
#### Generic
Broadcasts events directly in memory.

#### Log
Writes all event activity to a specific log destination. Useful for debugging.

### Emitter

The emitter takes care of broadcasting the event across all registered broadcasters.
Using the addListener method in the emitter adds the listener to all registered broadcasters.

## Getting Started

### Create an Event
    class UserLoggedIn implements IEvent
    {
        public int $UserId;

        public function __construct( int $userid )
        {
            $this->UserId = $userid;
        }
    }

### Create One or Many Listeners

    class UpdateAuditLog implements IListener
    {
	    public function event( $Event )
	    {
	    }
    }

    class EmailUser implements IListener
    {
	    public function event( $Event )
	    {
	    }
    }

### Register a Broadcaster

    $Emitter     = new Emitter();
    $Broadcaster = new Generic();

    $Emitter->registerBroadcaster( $Broadcaster );

### Add Listeners

    $Broadcaster->addListener( UserLoggedIn::class, new UpdateAuditLog() );
    $Broadcaster->addListener( UserLoggedIn::class, new EmailUser() );

### Emit the Event

    $Emitter->emit( new UserLoggedIn( $SomeUserId ) );


The event will be broadcast to all registered listeners.
Each listener will be called and passed information about the event what was emitted.
