<?php

namespace Neuron\Events;

/**
 * Generic listener interface.
 */
interface IListener
{
	/**
	 * Handles an event.
	 * @param IEvent $event
	 */
	public function event( IEvent $event );
}
