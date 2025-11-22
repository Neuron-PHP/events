<?php

namespace Neuron\Events\Broadcasters;

use Neuron\Events\IEvent;
use Neuron\Events\IListener;

/**
 * Register listeners and broadcast events to them.
 */
interface IBroadcaster
{
	/**
	 * Registers a listener for a specific event.
	 * @param string $eventName
	 * @param IListener $listener
	 * @return bool
	 */
	public function addListener( string $eventName, IListener $listener ) : bool;

	/**
	 * Broadcasts an event to all registered listeners.
	 * @param IEvent $event
	 */
	public function broadcast( IEvent $event ) : void;
}
