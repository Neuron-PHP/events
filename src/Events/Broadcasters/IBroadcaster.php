<?php

namespace Neuron\Events\Broadcasters;

use Neuron\Events\IEvent;
use Neuron\Events\IListener;

/**
 * Register listeners and broadcast events to them.
 */
interface IBroadcaster
{
	public function addListener( string $eventName, IListener $listener ) : bool;
	public function broadcast( IEvent $event ) : void;
}
