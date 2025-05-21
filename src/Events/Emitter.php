<?php
namespace Neuron\Events;

use Neuron\Events\Broadcasters\Generic;
use Neuron\Events\Broadcasters\IBroadcaster;

/**
 * Manages the emission of events to broadcasters.
 *
 * @see Generic Generic broadcaster.
 *
 */
class Emitter
{
	private array $_Broadcasters = [];

	/**
	 * Returns a list of all registered broadcasters.
	 *
	 * @return array Array of broadcasters.
	 */
	public function getBroadcasters(): array
	{
		return $this->_Broadcasters;
	}

	/**
	 * Emits an event across all registered broadcasters.
	 *
	 * @param $Event
	 */
	public function emit( $Event )
	{
		foreach( $this->_Broadcasters as $Broadcaster )
		{
			$Broadcaster->broadcast( $Event );
		}
	}

	/**
	 * Registers a broadcaster to emit events to.
	 *
	 * @param IBroadcaster $Broadcaster
	 */
	public function registerBroadcaster( IBroadcaster $Broadcaster ) : bool
	{
		foreach( $this->_Broadcasters as $Broadcaster)
		{
			if( $Broadcaster === $Broadcaster )
			{
				return false;
			}
		}

		$this->_Broadcasters[] = $Broadcaster;
		return true;
	}

	/**
	 * Registers an event with all broadcasters.
	 *
	 * @param string $EventName
	 * @param IListener $Listener
	 * @return bool
	 */
	public function addListener( string $EventName, IListener $Listener ) : bool
	{
		if( !count( $this->_Broadcasters ) )
		{
			return false;
		}

		$Result = true;
		foreach( $this->_Broadcasters as $Broadcaster )
		{
			if( $Broadcaster->addListener( $EventName, $Listener ) === false )
			{
				$Result = false;
			}
		}

		return $Result;
	}
}
