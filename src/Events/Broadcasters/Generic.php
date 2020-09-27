<?php

namespace Neuron\Events\Broadcasters;

use Neuron\Events\Broadcasters\IBroadcaster;
use Neuron\Events\IListener;

class Generic implements IBroadcaster
{
	private array $_Listeners = [];

	/**
	 * @param $Event
	 */
	public function broadcast( $Event )
	{
		foreach( $this->_Listeners as $EventName => $Listeners )
		{
			if( get_class( $Event ) == $EventName )
			{
				foreach( $Listeners as $Listener )
				{
					$Listener->event( $Event );
				}
			}
		}
	}

	/**
	 * @param string $EventName
	 * @param IListener $Listener
	 */
	public function addListener( string $EventName, IListener $Listener )
	{
		$this->_Listeners[ $EventName ][] = $Listener;
	}
}
