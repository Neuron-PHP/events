<?php
namespace Tests;

use Neuron\Events\IListener;

class SampleListener implements IListener
{
	public function event( $event )
	{
		$event->state += 1;
	}
}
