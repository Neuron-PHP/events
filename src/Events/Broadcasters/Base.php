<?php

namespace Neuron\Events\Broadcasters;

use Neuron\Events\IEvent;
use Neuron\Events\IListener;

/**
 * Base class for broadcasters
 */
abstract class Base implements IBroadcaster
{
	protected array $listeners = [];

	/**
	 * Maps a listener class name or object to an event name.
	 *
	 * An event can have any number of listeners, called in the order they were
	 * added. Adding the same listener - the same class name, or the same object -
	 * to an event twice is refused, so it is not called twice.
	 *
	 * @param string $eventName
	 * @param mixed $listener IListener|string $listener
	 * @return bool False if the listener was already registered for the event.
	 */
	public function addListener( string $eventName, mixed $listener ) : bool
	{
		if( in_array( $listener, $this->listeners[ $eventName ] ?? [], true ) )
		{
			return false;
		}

		$this->listeners[ $eventName ][] = $listener;
		return true;
	}

	/**
	 * Broadcasts an event to all registered listeners.
	 * @param IEvent $event
	 */
	abstract public function broadcast( IEvent $event ) : void;
}
