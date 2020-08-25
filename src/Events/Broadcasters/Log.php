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
class Log implements IBroadcaster
{
	private array   $_Listeners;
	private ILogger $_Logger;

	function __construct( ILogger $Logger )
	{
		$this->_Logger = $Logger;
	}

	/**
	 * @param string $EventName
	 * @param IListener $Listener
	 */
	public function addListener( string $EventName, IListener $Listener )
	{
		$this->_Listeners[ $EventName ][] = $Listener;
	}

	/**
	 * @param $Event
	 * @return mixed
	 */
	public function broadcast( $Event )
	{
		$this->_Logger->info( get_class( $Event ) );
	}
}
