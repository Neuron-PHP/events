<?php

namespace Tests\Events;

use Neuron\Events\Broadcasters\Generic;
use Neuron\Events\Emitter;
use PHPUnit\Framework\TestCase;
use Tests\EventTest;
use Tests\ListenerTest;

class EmitterTest extends TestCase
{

	public function testGetBroadcasters()
	{
		$emitter     = new Emitter();
		$broadcaster = new Generic();

		$emitter->registerBroadcaster( $broadcaster );

		$broadcasters = $emitter->getBroadcasters();

		$this->assertEquals( 1, count( $broadcasters ) );
	}

	public function testAddListener()
	{
		$emitter = new Emitter();

		$listener = new ListenerTest();

		$this->assertFalse( $emitter->addListener( EventTest::class, $listener ) );

		$broadcaster = new Generic();

		$emitter->registerBroadcaster( $broadcaster );

		$emitter->addListener( EventTest::class, $listener );

		$this->assertFalse( $emitter->addListener( EventTest::class, $listener ) );
	}
}
