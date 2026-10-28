<?php
namespace Tests;

use Neuron\Events\IEvent;

class SampleEvent implements IEvent
{
	public int $state = 0;
}
