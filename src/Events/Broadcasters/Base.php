<?php

namespace Neuron\Events\Broadcasters;

use Neuron\Events\IEvent;
use Neuron\Events\IListener;

/**
 * Base class for broadcasters
 */
abstract class Base implements IBroadcaster
{
	protected array $_Listeners = [];

	/**
	 * Maps a listener class nome or object to an event name.
	 * @param string $EventName
	 * @param mixed $Listener IListener|string $Listener
	 * @return bool
	 */
	public function addListener( string $EventName, mixed $Listener ) : bool
	{
		if( array_key_exists( $EventName, $this->_Listeners ) )
		{
			return false;
		}

		$this->_Listeners[ $EventName ][] = $Listener;
		return true;
	}

	/**
	 * Broadcasts an event to all registered listeners.
	 * @param IEvent $Event
	 */
	abstract public function broadcast( IEvent $Event ) : void;
}
