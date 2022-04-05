<?php

namespace Neuron\Events\Broadcasters;

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
	 * @param mixed IListener|string $Listener
	 */
	public function addListener( string $EventName, mixed $Listener ) : void
	{
		$this->_Listeners[ $EventName ][] = $Listener;
	}

	abstract public function broadcast( $Event ) : void;
}
