<?php

namespace Neuron\Events\Broadcasters;

use Neuron\Events\IListener;

interface IBroadcaster
{
	public function addListener( string $EventName, IListener $Listener );
	public function broadcast( $Event );
}
