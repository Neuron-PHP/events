<?php
/**
 *
 *
 */
namespace Neuron\Events\Broadcasters;

/**
 * Generic broadcaster that dispatches events inline.
 */
class Generic extends Base
{
	/**
	 * Broadcasts an event to all listeners registered to the event class.
	 * @param $Event
	 */
	public function broadcast( $Event ) : void
	{
		foreach( $this->_Listeners as $EventName => $Listeners )
		{
			if( get_class( $Event ) == $EventName )
			{
				foreach( $Listeners as $Listener )
				{
					if( is_string( $Listener ) )
					{
						$Listener = new $Listener();
					}

					$Listener->event( $Event );
				}
			}
		}
	}
}
