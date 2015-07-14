<?php

namespace Phalcon\Session;

interface AdapterInterface
{

	/**
	 * Starts session, optionally using an adapter
	 *
	 * @return void
	 */
	public function start();

	/**
	 * Sets session options
	 * 
	 * @param array $options
	 *
	 * @return void
	 */
	public function setOptions(array $options);

	/**
	 * Get internal options
	 *
	 * @return array
	 */
	public function getOptions();

	/**
	 * Gets a session variable from an application context
	 *
	 * @param string $index
	 * @param mixed $defaultValue
	 * 
	 * @return void
	 */
	public function get($index, $defaultValue=null);

	/**
	 * Sets a session variable in an application context
	 * 
	 * @param string $index
	 * @param string $value
	 *
	 *
	 * @return void
	 */
	public function set($index, $value);

	/**
	 * Check whether a session variable is set in an application context
	 * 
	 * @param string $index
	 *
	 * @return boolean
	 */
	public function has($index);

	/**
	 * Removes a session variable from an application context
	 * 
	 * @param string $index
	 *
	 * @return void
	 */
	public function remove($index);

	/**
	 * Returns active session id
	 *
	 * @return string
	 */
	public function getId();

	/**
	 * Check whether the session has been started
	 *
	 * @return boolean
	 */
	public function isStarted();

	/**
	 * Destroys the active session
	 *
	 * @return boolean
	 */
	public function destroy();

}
