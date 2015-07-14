<?php

namespace Phalcon\Paginator;

interface AdapterInterface
{

	/**
	 * Phalcon\Paginator\AdapterInterface constructor
	 * 
	 * @param array $config
	 */
	public function __construct(array $config);

	/**
	 * Set the current page number
	 * 
	 * @param int $page
	 *
	 * @return void
	 */
	public function setCurrentPage($page);

	/**
	 * Returns a slice of the resultset to show in the pagination
	 *
	 * @return \stdClass
	 */
	public function getPaginate();

	/**
	 * Set current rows limit
	 * 
	 * @param int $limit
	 *
	 * @return void
	 */
	public function setLimit($limit);

	/**
	 * Get current rows limit
	 *
	 * @return int
	 */
	public function getLimit();

}
