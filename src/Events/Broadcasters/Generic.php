<?php
/**
 *
 *
 */
namespace Neuron\Events\Broadcasters;

class Generic extends Base
{
	/**
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
