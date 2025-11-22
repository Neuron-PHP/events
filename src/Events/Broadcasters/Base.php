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
	 * Maps a listener class nome or object to an event name.
	 * @param string $eventName
	 * @param mixed $listener IListener|string $listener
	 * @return bool
	 */
	public function addListener( string $eventName, mixed $listener ) : bool
	{
		if( array_key_exists( $eventName, $this->listeners ) )
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
