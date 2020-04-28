<?php

namespace Neuron\Event;

class Emitter
{
	private array $_Events;
	private array $_Broadcasters;

	public function addEvent()
	{}

	public function removeEvent()
	{}

	public function emit( $Event )
	{
		foreach( $this->_Broadcasters as $Broadcaster )
		{
			$Broadcaster->broadcast( $Event );
		}
	}

	public function registerBroadcaster( IBroadcaster $Broadcaster )
	{
		$this->_Broadcasters[] = $Broadcaster;
	}
}
