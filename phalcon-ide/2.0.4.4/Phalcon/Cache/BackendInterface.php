<?php

namespace Phalcon\Cache;

interface BackendInterface
{

	/**
	 * Starts a cache. The keyname allows to identify the created fragment
	 *
	 * @param int|string $keyName
	 * @param int $lifetime
	 * 
	 * @return void
	 */
	public function start($keyName, $lifetime=null);

	/**
	 * Stops the frontend without store any cached content
	 * 
	 * @param boolean $stopBuffer
	 *
	 *
	 * @return void
	 */
	public function stop($stopBuffer=true);

	/**
	 * Returns front-end instance adapter related to the back-end
	 *
	 * @return void
	 */
	public function getFrontend();

	/**
	 * Returns the backend options
	 *
	 * @return void
	 */
	public function getOptions();

	/**
	 * Checks whether the last cache is fresh or cached
	 *
	 * @return boolean
	 */
	public function isFresh();

	/**
	 * Checks whether the cache has starting buffering or not
	 *
	 * @return boolean
	 */
	public function isStarted();

	/**
	 * Sets the last key used in the cache
	 * 
	 * @param string $lastKey
	 *
	 *
	 * @return void
	 */
	public function setLastKey($lastKey);

	/**
	 * Gets the last key stored by the cache
	 *
	 * @return void
	 */
	public function getLastKey();

	/**
	 * Returns a cached content
	 *
	 * @param int|string $keyName
	 * @param int $lifetime
	 * 
	 * @return void
	 */
	public function get($keyName, $lifetime=null);

	/**
	 * Stores cached content into the file backend and stops the frontend
	 * 
	 * @param int|string $keyName
	 * @param string $content
	 * @param int $lifetime
	 * @param boolean $stopBuffer
	 *
	 *
	 * @return void
	 */
	public function save($keyName=null, $content=null, $lifetime=null, $stopBuffer=true);

	/**
	 * Deletes a value from the cache by its key
	 *
	 * @param int|string $keyName
	 * 
	 * @return void
	 */
	public function delete($keyName);

	/**
	 * Query the existing cached keys
	 *
	 * @param string $prefix
	 * 
	 * @return void
	 */
	public function queryKeys($prefix=null);

	/**
	 * Checks if cache exists and it hasn't expired
	 *
	 * @param string $keyName
	 * @param int $lifetime
	 * 
	 * @return void
	 */
	public function exists($keyName=null, $lifetime=null);

}
