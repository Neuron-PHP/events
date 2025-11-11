<?php
namespace Tests;

use Neuron\Events\IEvent;

class EventTest implements IEvent
{
	public int $state = 0;
}
