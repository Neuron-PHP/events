<?php

namespace Neuron\Event;

interface IBroadcaster
{
	public function addListener( string $EventName, IListener $Listener );
	public function broadcast( $Event );
}
