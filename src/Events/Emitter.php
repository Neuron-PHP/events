<?php
namespace Neuron\Events;

use Neuron\Events\Broadcasters\Generic;
use Neuron\Events\Broadcasters\IBroadcaster;

/**
 * Central event emitter that manages event broadcasting across multiple channels.
 * 
 * The Emitter class serves as the core component of the Neuron event system,
 * coordinating event distribution across registered broadcasters. It provides
 * a unified interface for event emission while supporting multiple broadcasting
 * strategies through the IBroadcaster interface.
 * 
 * Key responsibilities:
 * - Register and manage multiple event broadcasters
 * - Emit events to all registered broadcasters simultaneously
 * - Register listeners across all broadcasters for consistent event handling
 * - Prevent duplicate broadcaster registrations
 * - Provide centralized event coordination for the entire application
 * 
 * The emitter follows a publish-subscribe pattern where events are published
 * to all registered broadcasters, which then distribute them to their
 * respective listeners based on event names and filtering criteria.
 * 
 * @package Neuron\Events
 * 
 * @see Generic Default generic broadcaster implementation
 * @see IBroadcaster Broadcaster interface
 * @see IListener Event listener interface
 * 
 * @example
 * ```php
 * // Create emitter and register broadcasters
 * $emitter = new Emitter();
 * $emitter->registerBroadcaster(new Generic());
 * $emitter->registerBroadcaster(new SlackBroadcaster());
 * 
 * // Register listeners across all broadcasters
 * $emitter->addListener('user.login', new LoginNotificationListener());
 * $emitter->addListener('order.completed', new OrderCompletionListener());
 * 
 * // Emit events to all broadcasters
 * $emitter->emit(new UserLoginEvent($user));
 * $emitter->emit(new OrderCompletedEvent($order));
 * ```
 */
class Emitter
{
	private array $_Broadcasters = [];

	/**
	 * Returns a list of all registered broadcasters.
	 *
	 * @return array Array of broadcasters.
	 */
	public function getBroadcasters(): array
	{
		return $this->_Broadcasters;
	}

	/**
	 * Emits an event across all registered broadcasters.
	 *
	 * @param $Event
	 */
	public function emit( $Event ): void
	{
		foreach( $this->_Broadcasters as $Broadcaster )
		{
			$Broadcaster->broadcast( $Event );
		}
	}

	/**
	 * Registers a broadcaster to emit events to.
	 *
	 * @param IBroadcaster $NewBroadcaster
	 * @return bool
	 */
	public function registerBroadcaster( IBroadcaster $NewBroadcaster ) : bool
	{
		foreach( $this->_Broadcasters as $Broadcaster)
		{
			if( $Broadcaster === $NewBroadcaster )
			{
				return false;
			}
		}

		$this->_Broadcasters[] = $NewBroadcaster;
		return true;
	}

	/**
	 * Registers an event with all broadcasters.
	 *
	 * @param string $EventName
	 * @param IListener $Listener
	 * @return bool
	 */
	public function addListener( string $EventName, IListener $Listener ) : bool
	{
		if( !count( $this->_Broadcasters ) )
		{
			return false;
		}

		$Result = true;
		foreach( $this->_Broadcasters as $Broadcaster )
		{
			if( $Broadcaster->addListener( $EventName, $Listener ) === false )
			{
				$Result = false;
			}
		}

		return $Result;
	}
}
