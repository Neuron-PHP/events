<?php
/**
 *
 *
 */
namespace Neuron\Events\Broadcasters;

use Neuron\Events\IEvent;

/**
 * Generic broadcaster that dispatches events inline.
 */
class Generic extends Base
{
	/**
	 * Broadcasts an event to all listeners registered to the event class.
	 * @param IEvent $event
	 */
	public function broadcast( IEvent $event ) : void
	{
		foreach( $this->_listeners as $eventName => $listeners )
		{
			if( get_class( $event ) == $eventName )
			{
				foreach( $listeners as $listener )
				{
					if( is_string( $listener ) )
					{
						$listener = new $listener();
					}

					$listener->event( $event );
				}
			}
		}
	}
}
