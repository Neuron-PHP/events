<?php
/**
 * @package Neuron\Events\Broadcasters
 *
 */

namespace Neuron\Events\Broadcasters;

use Neuron\Events\IListener;
use Neuron\Log\ILogger;

/**
 * Class Log
 */
class Log extends Base
{
	private ILogger $_Logger;

	function __construct( ILogger $Logger )
	{
		$this->_Logger = $Logger;
	}

	/**
	 * @param string $EventName
	 * @param mixed $Listener
	 */
	public function addListener( string $EventName, mixed $Listener ) : void
	{
		$this->_Listeners[ $EventName ][] = $Listener;
	}

	/**
	 * @param $Event
	 * @return mixed
	 */
	public function broadcast( $Event ) : void
	{
		$this->_Logger->info( get_class( $Event ) );
	}
}
