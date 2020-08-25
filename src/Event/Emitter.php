<?php
/**
 * @package Neuron\Event
 *
 */

namespace Neuron\Event;

/**
 * Class Emitter
 */
class Emitter
{
	private array $_Broadcasters;

	/**
	 * @return array
	 */
	public function getBroadcasters(): array
	{
		return $this->_Broadcasters;
	}

	/**
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
	 * @param IBroadcaster $Broadcaster
	 */
	public function registerBroadcaster( IBroadcaster $Broadcaster )
	{
		$this->_Broadcasters[] = $Broadcaster;
	}
}
