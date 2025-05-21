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
		$Emitter     = new Emitter();
		$Broadcaster = new Generic();

		$Emitter->registerBroadcaster( $Broadcaster );

		$Broadcasters = $Emitter->getBroadcasters();

		$this->assertEquals( 1, count( $Broadcasters ) );
	}

	public function testAddListener()
	{
		$Emitter = new Emitter();

		$Listener = new ListenerTest();

		$this->assertFalse( $Emitter->addListener( EventTest::class, $Listener ) );

		$Broadcaster = new Generic();

		$Emitter->registerBroadcaster( $Broadcaster );

		$Emitter->addListener( EventTest::class, $Listener );

		$this->assertFalse( $Emitter->addListener( EventTest::class, $Listener ) );
	}
}
