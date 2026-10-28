<?php

namespace Tests\Events\Broadcasters;

use Neuron\Events\Broadcasters\Log;
use Neuron\Events\Emitter;
use Neuron\Log\Destination\Memory;
use Neuron\Log\Format\Raw;
use Neuron\Log\Logger;
use PHPUnit\Framework\TestCase;

class LogTest extends TestCase
{
	/**
	 * @throws \Exception
	 */
	public function testBroadcaster()
	{
		$memory      = new Memory( new Raw() );
		$logger      = new Logger( $memory );
		$broadcaster = new Log( $logger );
		$emitter     = new Emitter();
		$event       = new \Tests\SampleEvent();

		$logger->setRunLevel( 'debug' );
		$emitter->registerBroadcaster( $broadcaster );

		$emitter->emit( $event );

		$this->assertEquals(
			"Tests\SampleEvent\n",
			$memory->getData()
		);
	}
}
