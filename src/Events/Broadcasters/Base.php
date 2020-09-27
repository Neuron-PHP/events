<?php

namespace Neuron\Events\Broadcasters;

use Neuron\Events\IListener;

abstract class Base implements IBroadcaster
{
	protected array $_Listeners;

	/**
	 * @param string $EventName
	 * @param IListener $Listener
	 */
	public function addListener( string $EventName, IListener $Listener )
	{
		$this->_Listeners[ $EventName ][] = $Listener;
	}

	abstract public function broadcast( $Event );
}
