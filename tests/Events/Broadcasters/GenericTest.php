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

	/**
	 * Every listener added to an event is called, in the order added. Only the
	 * first used to be kept, and the rest were dropped without a word.
	 */
	public function testEveryListenerForAnEventIsCalledInOrder()
	{
		$broadcaster = new Generic();
		$emitter     = new Emitter();
		$event       = new EventTest();
		$calls       = [];

		$first  = new RecordingListener( 'first', $calls );
		$second = new RecordingListener( 'second', $calls );

		$this->assertTrue( $broadcaster->addListener( EventTest::class, $first ) );
		$this->assertTrue( $broadcaster->addListener( EventTest::class, $second ) );
		$this->assertTrue( $broadcaster->addListener( EventTest::class, ListenerTest::class ) );

		$emitter->registerBroadcaster( $broadcaster );

		$emitter->emit( $event );

		$this->assertEquals( [ 'first', 'second' ], $calls );
		$this->assertEquals( 1, $event->state, 'and the class-name listener' );
	}

	public function testTheSameObjectIsRefusedButAnotherOfItsClassIsNot()
	{
		$broadcaster = new Generic();
		$listener    = new ListenerTest();

		$this->assertTrue( $broadcaster->addListener( EventTest::class, $listener ) );
		$this->assertFalse( $broadcaster->addListener( EventTest::class, $listener ) );
		$this->assertTrue( $broadcaster->addListener( EventTest::class, new ListenerTest() ) );
	}
}

class RecordingListener implements \Neuron\Events\IListener
{
	public function __construct( private string $name, private array &$calls )
	{
	}

	public function event( $event )
	{
		$this->calls[] = $this->name;
	}
}
