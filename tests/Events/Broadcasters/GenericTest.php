<?php

namespace Tests\Events\Broadcasters;

use Neuron\Events\Broadcasters\Generic;
use Neuron\Events\Emitter;
use PHPUnit\Framework\TestCase;
use Tests\EventTest;
use Tests\ListenerTest;

class GenericTest extends TestCase
{
	public function testBroadcasterWithObject()
	{
		$Emitter     = new Emitter();
		$Broadcaster = new Generic();
		$Listener    = new ListenerTest();
		$Event       = new EventTest();

		$Emitter->registerBroadcaster( $Broadcaster );

		$Broadcaster->addListener( EventTest::class, $Listener );

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

	public function testRegisterWithEmitter()
	{
		$Emitter     = new Emitter();
		$Broadcaster = new Generic();
		$Listener    = new ListenerTest();
		$Event       = new EventTest();

		$Emitter->registerBroadcaster( $Broadcaster );

		$Emitter->addListener( EventTest::class, $Listener );

		$Emitter->emit( $Event );

		$this->assertEquals(
			1,
			$Event->State
		);
	}

	public function testDuplicateBroadcasters()
	{
		$Broadcaster = new Generic();
		$Emitter     = new Emitter();
		$Event       = new EventTest();

		$Broadcaster->addListener( EventTest::class, ListenerTest::class );

		$Emitter->registerBroadcaster( $Broadcaster );
		$Emitter->registerBroadcaster( $Broadcaster );

		$Emitter->emit( $Event );

		$this->assertEquals(
			1,
			$Event->State
		);
	}

	public function testDuplicateListeners()
	{
		$Broadcaster = new Generic();
		$Emitter     = new Emitter();
		$Event       = new EventTest();

		$Broadcaster->addListener( EventTest::class, ListenerTest::class );
		$Broadcaster->addListener( EventTest::class, ListenerTest::class );

		$Emitter->registerBroadcaster( $Broadcaster );

		$Emitter->emit( $Event );

		$this->assertEquals(
			1,
			$Event->State
		);
	}
}
