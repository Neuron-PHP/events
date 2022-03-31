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
			$Listener->State
		);
	}

	public function testBroadcasterWithClass()
	{
		$Memory      = new Memory( new Raw() );
		$Logger      = new Logger( $Memory );
		$Broadcaster = new Log( $Logger );

		$Emitter     = new Emitter();
		$Event       = new EventTest();

		$Logger->setRunLevel( 'debug' );

		$Broadcaster->addListener( EventTest::class, ListenerTest::class );

		$Emitter->registerBroadcaster( $Broadcaster );

		$Emitter->emit( $Event );

		$this->assertEquals(
			"Neuron\Events\EventTest\n",
			$Memory->getData()
		);
	}
}
