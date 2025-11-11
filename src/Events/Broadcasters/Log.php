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
	private ILogger $_logger;

	/**
	 * @param ILogger $logger
	 */
	function __construct( ILogger $logger )
	{
		$this->_logger = $logger;
	}

	/**
	 * Writes the name of the broadcast event to the configured log.
	 *
	 * @param $event
	 * @return void
	 */
	public function broadcast( $event ) : void
	{
		$this->_logger->info( get_class( $event ) );
	}
}
