<?php

namespace Neuron\Events;

use Neuron\Events\Broadcasters\Generic;
use PHPUnit\Framework\TestCase;

class EventTest implements IEvent
{
	public int $State = 1;
}

class ListenerTest implements IListener
{
	public int $State = 0;

	public function event( $Event )
	{
		$this->State = $Event->State;
	}
}

class GenericBroadcasterTest extends TestCase
{
	public function testBroadcaster()
	{
		$Emitter     = new Emitter();
		$Broadcaster = new Generic();
		$Listener    = new ListenerTest();
		$Event       = new EventTest();

		$Broadcaster->addListener( EventTest::class, $Listener );

		$Emitter->registerBroadcaster( $Broadcaster );

		$Emitter->emit( $Event );

		$this->assertEquals(
			1,
			$Listener->State
		);
	}
}
