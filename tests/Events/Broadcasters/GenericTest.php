<?php

namespace Neuron\Events;

use Neuron\Events\Broadcasters\Generic;
use Neuron\Events\Broadcasters\Log;
use Neuron\Log\Destination\Memory;
use Neuron\Log\Format\Raw;
use Neuron\Log\Logger;
use PHPUnit\Framework\TestCase;

class EventTest implements IEvent
{
	public int $State = 0;
}

class ListenerTest implements IListener
{
	public function event( $Event )
	{
		$Event->State = 1;
	}
}

class GenericTest extends TestCase
{
	public function testBroadcasterWithObject()
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
			$Event->State
		);
	}

	public function testBroadcasterWithClass()
	{
		$Broadcaster = new Generic();
		$Emitter     = new Emitter();
		$Event       = new EventTest();

		$Broadcaster->addListener( EventTest::class, ListenerTest::class );

		$Emitter->registerBroadcaster( $Broadcaster );

		$Emitter->emit( $Event );

		$this->assertEquals(
			1,
			$Event->State
		);
	}
}
