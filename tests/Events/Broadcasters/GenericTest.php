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
		$emitter     = new Emitter();
		$broadcaster = new Generic();
		$listener    = new ListenerTest();
		$event       = new EventTest();

		$emitter->registerBroadcaster( $broadcaster );

		$broadcaster->addListener( EventTest::class, $listener );

		$emitter->emit( $event );

		$this->assertEquals(
			1,
			$event->state
		);
	}

	public function testBroadcasterWithClass()
	{
		$broadcaster = new Generic();
		$emitter     = new Emitter();
		$event       = new EventTest();

		$broadcaster->addListener( EventTest::class, ListenerTest::class );

		$emitter->registerBroadcaster( $broadcaster );

		$emitter->emit( $event );

		$this->assertEquals(
			1,
			$event->state
		);
	}

	public function testRegisterWithEmitter()
	{
		$emitter     = new Emitter();
		$broadcaster = new Generic();
		$listener    = new ListenerTest();
		$event       = new EventTest();

		$emitter->registerBroadcaster( $broadcaster );

		$emitter->addListener( EventTest::class, $listener );

		$emitter->emit( $event );

		$this->assertEquals(
			1,
			$event->state
		);
	}

	public function testDuplicateBroadcasters()
	{
		$broadcaster = new Generic();
		$emitter     = new Emitter();
		$event       = new EventTest();

		$broadcaster->addListener( EventTest::class, ListenerTest::class );

		$emitter->registerBroadcaster( $broadcaster );
		$emitter->registerBroadcaster( $broadcaster );

		$emitter->emit( $event );

		$this->assertEquals(
			1,
			$event->state
		);
	}

	public function testDuplicateListeners()
	{
		$broadcaster = new Generic();
		$emitter     = new Emitter();
		$event       = new EventTest();

		$broadcaster->addListener( EventTest::class, ListenerTest::class );
		$broadcaster->addListener( EventTest::class, ListenerTest::class );

		$emitter->registerBroadcaster( $broadcaster );

		$emitter->emit( $event );

		$this->assertEquals(
			1,
			$event->state
		);
	}
}
