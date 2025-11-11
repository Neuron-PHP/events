<?php
namespace Tests;

use Neuron\Events\IListener;

class ListenerTest implements IListener
{
	public function event( $event )
	{
		$event->state += 1;
	}
}
