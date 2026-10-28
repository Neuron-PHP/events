<?php

namespace Tests\Events\Broadcasters;

use Neuron\Events\Broadcasters\Generic;
use Neuron\Events\Emitter;
use PHPUnit\Framework\TestCase;
use Tests\SampleEvent;
use Tests\SampleListener;

class GenericTest extends TestCase
{
	public function testBroadcasterWithObject()
	{
		$emitter     = new Emitter();
		$broadcaster = new Generic();
		$listener    = new SampleListener();
		$event       = new SampleEvent();

		$emitter->registerBroadcaster( $broadcaster );

		$broadcaster->addListener( SampleEvent::class, $listener );

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
		$event       = new SampleEvent();

		$broadcaster->addListener( SampleEvent::class, SampleListener::class );

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
		$listener    = new SampleListener();
		$event       = new SampleEvent();

		$emitter->registerBroadcaster( $broadcaster );

		$emitter->addListener( SampleEvent::class, $listener );

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
		$event       = new SampleEvent();

		$broadcaster->addListener( SampleEvent::class, SampleListener::class );

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
		$event       = new SampleEvent();

		$broadcaster->addListener( SampleEvent::class, SampleListener::class );
		$broadcaster->addListener( SampleEvent::class, SampleListener::class );

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
		$event       = new SampleEvent();
		$calls       = [];

		$first  = new RecordingListener( 'first', $calls );
		$second = new RecordingListener( 'second', $calls );

		$this->assertTrue( $broadcaster->addListener( SampleEvent::class, $first ) );
		$this->assertTrue( $broadcaster->addListener( SampleEvent::class, $second ) );
		$this->assertTrue( $broadcaster->addListener( SampleEvent::class, SampleListener::class ) );

		$emitter->registerBroadcaster( $broadcaster );

		$emitter->emit( $event );

		$this->assertEquals( [ 'first', 'second' ], $calls );
		$this->assertEquals( 1, $event->state, 'and the class-name listener' );
	}

	public function testTheSameObjectIsRefusedButAnotherOfItsClassIsNot()
	{
		$broadcaster = new Generic();
		$listener    = new SampleListener();

		$this->assertTrue( $broadcaster->addListener( SampleEvent::class, $listener ) );
		$this->assertFalse( $broadcaster->addListener( SampleEvent::class, $listener ) );
		$this->assertTrue( $broadcaster->addListener( SampleEvent::class, new SampleListener() ) );
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
