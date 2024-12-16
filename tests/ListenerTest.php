<?php
namespace Tests;

use Neuron\Events\IListener;

class ListenerTest implements IListener
{
	public function event( $Event )
	{
		$Event->State += 1;
	}
}
