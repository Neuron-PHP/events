<?php

namespace Tests\Events\Broadcasters;

use Neuron\Events\Broadcasters\Log;
use Neuron\Events\Emitter;
use Neuron\Events\EventTest;
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
		$Memory      = new Memory( new Raw() );
		$Logger      = new Logger( $Memory );
		$Broadcaster = new Log( $Logger );
		$Emitter     = new Emitter();
		$Event       = new \Tests\EventTest();

		$Logger->setRunLevel( 'debug' );
		$Emitter->registerBroadcaster( $Broadcaster );

		$Emitter->emit( $Event );

		$this->assertEquals(
			"Tests\EventTest\n",
			$Memory->getData()
		);
	}
}
