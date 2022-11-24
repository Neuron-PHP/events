<?php

namespace Neuron\Events\Broadcasters;

use Neuron\Events\IListener;

/**
 * Interface for broadcasters.
 */
interface IBroadcaster
{
	public function addListener( string $EventName, IListener $Listener ) : void;
	public function broadcast( $Event ) : void;
}
