<?php

namespace Neuron\Events;

/**
 * Core event interface for the Neuron event system.
 * 
 * This interface defines the contract for all events in the framework.
 * Events represent occurrences within the application that other components
 * can listen for and respond to, enabling decoupled, event-driven architecture.
 * 
 * Implementation classes should contain relevant data about the event
 * and provide methods to access that data. Events are typically immutable
 * once created and dispatched through the EventEmitter.
 * 
 * @package Neuron\Events
 * 
 * @example
 * ```php
 * class UserRegisteredEvent implements IEvent
 * {
 *     private $userId;
 *     private $email;
 *     
 *     public function __construct(int $userId, string $email)
 *     {
 *         $this->userId = $userId;
 *         $this->email = $email;
 *     }
 *     
 *     public function getUserId(): int { return $this->userId; }
 *     public function getEmail(): string { return $this->email; }
 * }
 * ```
 */
interface IEvent
{
}
