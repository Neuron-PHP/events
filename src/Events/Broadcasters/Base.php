<?php

namespace Neuron\Events\Broadcasters;

use Neuron\Events\IListener;

abstract class Base implements IBroadcaster
{
	protected array $_Listeners = [];

	/**
	 * @param string $EventName
	 * @param mixed IListener|string $Listener
	 */
	public function addListener( string $EventName, mixed $Listener ) : void
	{
		$this->_Listeners[ $EventName ][] = $Listener;
	}

	abstract public function broadcast( $Event ) : void;
}
