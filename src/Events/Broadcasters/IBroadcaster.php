<?php

namespace Neuron\Events\Broadcasters;

use Neuron\Events\IEvent;
use Neuron\Events\IListener;

/**
 * Register listeners and broadcast events to them.
 */
interface IBroadcaster
{
	public function addListener( string $EventName, IListener $Listener ) : bool;
	public function broadcast( IEvent $Event ) : void;
}
