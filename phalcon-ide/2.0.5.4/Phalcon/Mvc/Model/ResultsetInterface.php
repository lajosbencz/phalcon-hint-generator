<?php

namespace Phalcon\Mvc\Model;

interface ResultsetInterface
{

	/**
	 * Returns the internal type of data retrieval that the resultset is using
	 *
	 * @return int
	 */
	public function getType();

	/**
	 * Get first row in the resultset
	 *
	 * @return void
	 */
	public function getFirst();

	/**
	 * Get last row in the resultset
	 *
	 * @return void
	 */
	public function getLast();

	/**
	 * Set if the resultset is fresh or an old one cached
	 * 
	 * @param boolean $isFresh
	 *
	 * @return void
	 */
	public function setIsFresh($isFresh);

	/**
	 * Tell if the resultset if fresh or an old one cached
	 *
	 * @return boolean
	 */
	public function isFresh();

	/**
	 * Returns the associated cache for the resultset
	 *
	 * @return void
	 */
	public function getCache();

	/**
	 * Returns a complete resultset as an array, if the resultset has a big number of rows
	 * it could consume more memory than currently it does.
	 *
	 * @return array
	 */
	public function toArray();

}
