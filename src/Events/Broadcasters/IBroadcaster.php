<?php

namespace Neuron\Events\Broadcasters;

use Neuron\Events\IListener;

interface IBroadcaster
{
	public function addListener( string $EventName, IListener $Listener ) : void;
	public function broadcast( $Event ) : void;
}
