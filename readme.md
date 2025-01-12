[![Build Status](https://app.travis-ci.com/Neuron-PHP/events.svg?token=F8zCwpT7x7Res7J2N4vF&branch=master)](https://app.travis-ci.com/Neuron-PHP/events)
# Neuron-PHP Events

## Overview
The Neuron Events component provides a flexible framework for managing events within
your application.

## Installation

Install php composer from https://getcomposer.org/

Install the neuron events component:

    composer require neuron-php/events

### Events

An Event is triggered in response to a significant change in state within a system.

### Emitter

An Emitter acts as a central hub for dispatching events. This emitter is responsible 
for managing and reporting events to one or multiple Broadcasters.

### Broadcasters

A Broadcaster is a component responsible for dispatching events to multiple listeners or subscribers.

### Listeners

Listeners are the components that respond to events and execute specific actions.

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

    $Emitter->addListener( UserLoggedIn::class, new UpdateAuditLog() );
    $Emitter->addListener( UserLoggedIn::class, new EmailUser() );

Adding a listener to the emitter will add it to all registered broadcasters. You
can add listeners to specific broadcasters by using the addListener method on 
the broadcaster.

### Emit the Event

    $Emitter->emit( new UserLoggedIn( $SomeUserId ) );

The event will be broadcast to all registered listeners.

Each listener will be called and passed the event object.

# More Information

The Neuron Core component contains an Event singleton that acts as a cross-cutting
concern for the entire application, simplifying the process of event management.

The Core component can found at [neuron-php/core](http://github.com/neuron-php/core)

You can read more about the Neuron components at [neuronphp.com](http://neuronphp.com)
