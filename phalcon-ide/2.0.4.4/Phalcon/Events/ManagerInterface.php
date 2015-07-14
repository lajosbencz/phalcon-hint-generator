<?php

namespace Phalcon\Events;

interface ManagerInterface
{

	/**
	 * Attach a listener to the events manager
	 * 
	 * @param string $eventType
	 * @param object|callable $handler
	 *
	 *
	 * @return void
	 */
	public function attach($eventType, $handler);

	/**
	 * Detach the listener from the events manager
	 * 
	 * @param string $eventType
	 * @param object $handler
	 *
	 *
	 * @return void
	 */
	public function detach($eventType, $handler);

	/**
	 * Removes all events from the EventsManager
	 * 
	 * @param string $type
	 *
	 * @return void
	 */
	public function detachAll($type=null);

	/**
	 * Fires a event in the events manager causing that the acive listeners will be notified about it
	 *
	 * @param string $eventType
	 * @param object $source
	 * @param mixed $data
	 * 
	 * @return void
	 */
	public function fire($eventType, $source, $data=null);

	/**
	 * Returns all the attached listeners of a certain type
	 *
	 * @param string $type
	 * 
	 * @return void
	 */
	public function getListeners($type);

}
