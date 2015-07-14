<?php

namespace Phalcon\Filter;

interface UserFilterInterface
{

	/**
	 * Filters a value
	 * 
	 * @param $value
	 *
	 * @return void
	 */
	public function filter($value);

}
