<?php

namespace Neuron\Events\Cli;

use Neuron\Cli\Commands\Registry;

/**
 * CLI provider for the Events component.
 * Registers all events-related CLI commands.
 */
class Provider
{
	/**
	 * Register events commands with the CLI registry
	 *
	 * @param Registry $registry CLI Registry instance
	 * @return void
	 */
	public static function register( Registry $registry ): void
	{
		// Register event generator
		$registry->register(
			'event:generate',
			'Neuron\\Events\\Cli\\Commands\\Generate\\EventCommand'
		);

		// Register listener generator
		$registry->register(
			'listener:generate',
			'Neuron\\Events\\Cli\\Commands\\Generate\\ListenerCommand'
		);
	}
}
