<?php

namespace Neuron\Events\Broadcasters;

use Neuron\Events\IListener;
use Neuron\Log\ILogger;

/**
 * Broadcaster that writes all dispatched events to a log file.
 * Does nothing with the actual events. This broadcaster is for
 * debugging/diagnosing and to be used in conjunction with a
 * functional broadcaster.
 */
class Log extends Base
{
	private ILogger $_Logger;

	/**
	 * @param ILogger $Logger
	 */
	function __construct( ILogger $Logger )
	{
		$this->_Logger = $Logger;
	}

	/**
	 * Maps a listener classname or object to an event name.
	 *
	 * @param string $EventName
	 * @param mixed $Listener
	 */
	public function addListener( string $EventName, mixed $Listener ) : void
	{
		$this->_Listeners[ $EventName ][] = $Listener;
	}

	/**
	 * Writes the name of the broadcast event to the configured log.
	 *
	 * @param $Event
	 * @return mixed
	 */
	public function broadcast( $Event ) : void
	{
		$this->_Logger->info( get_class( $Event ) );
	}
}
