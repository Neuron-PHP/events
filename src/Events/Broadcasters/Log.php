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
	private ILogger $logger;

	/**
	 * @param ILogger $logger
	 */
	function __construct( ILogger $logger )
	{
		$this->setLogger( $logger );
	}

	/**
	 * Sets the logger to use.
	 *
	 * @param ILogger $logger
	 * @return self
	 */
	public function setLogger( ILogger $logger ) : self
	{
		$this->logger = $logger;
		return $this;
	}

	/**
	 * Writes the name of the broadcast event to the configured log.
	 *
	 * @param $event
	 * @return void
	 */
	public function broadcast( $event ) : void
	{
		$this->logger->info( get_class( $event ) );
	}
}
