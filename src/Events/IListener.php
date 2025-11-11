<?php

namespace Neuron\Events;

/**
 * Generic listener interface.
 */
interface IListener
{
	public function event( IEvent $event );
}
