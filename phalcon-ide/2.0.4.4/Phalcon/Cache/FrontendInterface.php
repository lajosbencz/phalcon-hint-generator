<?php

namespace Phalcon\Cache;

interface FrontendInterface
{

	/**
	 * Returns the cache lifetime
	 *
	 * @return int
	 */
	public function getLifetime();

	/**
	 * Check whether if frontend is buffering output
	 *
	 * @return boolean
	 */
	public function isBuffering();

	/**
	 * Starts the frontend
	 *
	 * @return void
	 */
	public function start();

	/**
	 * Returns output cached content
	 *
	 * @return void
	 */
	public function getContent();

	/**
	 * Stops the frontend
	 *
	 * @return void
	 */
	public function stop();

	/**
	 * Serializes data before storing it
	 * 
	 * @param mixed $data
	 *
	 *
	 * @return void
	 */
	public function beforeStore($data);

	/**
	 * Unserializes data after retrieving it
	 * 
	 * @param mixed $data
	 *
	 *
	 * @return void
	 */
	public function afterRetrieve($data);

}
