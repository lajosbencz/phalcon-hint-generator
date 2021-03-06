<?php

namespace Phalcon\Cache\Backend;

use Phalcon\Cache\Backend;
use Phalcon\Cache\BackendInterface;
use Phalcon\Cache\Exception;
use Phalcon\Cache\FrontendInterface;


class Memcache extends Backend implements BackendInterface
{

	protected $_memcache = null;



	/**
	 * Phalcon\Cache\Backend\Memcache constructor
	 * 
	 * @param FrontendInterface $frontend
	 * @param array $options
	 *
	 */
	public function __construct(FrontendInterface $frontend, $options=null) {}

	/**
	 * Create internal connection to memcached
	 *
	 * @return void
	 */
	public function _connect() {}

	/**
	 * Returns a cached content
	 *
	 * @param mixed $keyName
	 * @param mixed $lifetime
	 * 
	 * @return mixed
	 */
	public function get($keyName, $lifetime=null) {}

	/**
	 * Stores cached content into the file backend and stops the frontend
	 * 
	 * @param mixed $keyName
	 * @param mixed $content
	 * @param mixed $lifetime
	 * @param boolean $stopBuffer
	 *
	 *
	 * @return void
	 */
	public function save($keyName=null, $content=null, $lifetime=null, $stopBuffer=true) {}

	/**
		 * Check if a connection is created or make a new one
	 * 
	 * @param mixed $keyName
		 *
	 * @return mixed
	 */
	public function delete($keyName) {}

	/**
		 * Delete the key from memcached
	 * 
	 * @param $prefix
		 *
	 * @return array
	 */
	public function queryKeys($prefix=null) {}

	/**
		 * Get the key from memcached
	 * 
	 * @param $keyName
	 * @param $lifetime
		 *
	 * @return boolean
	 */
	public function exists($keyName=null, $lifetime=null) {}

	/**
	 * Increment of given $keyName by $value
	 *
	 * @param string $keyName
	 * @param $value
	 * @param $lifetime
	 * 
	 * @return mixed
	 */
	public function increment($keyName=null, $value=null) {}

	/**
	 * Decrement of $keyName by given $value
	 *
	 * @param string $keyName
	 * @param $value
	 * 
	 * @return mixed
	 */
	public function decrement($keyName=null, $value=null) {}

	/**
	 * Immediately invalidates all existing items.
	 *
	 * @return boolean
	 */
	public function flush() {}

}
