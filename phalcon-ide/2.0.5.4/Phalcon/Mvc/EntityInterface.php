<?php

namespace Phalcon\Mvc;

interface EntityInterface
{

	/**
	 * Reads an attribute value by its name
	 *
	 * @param string $attribute
	 * 
	 * @return void
	 */
	public function readAttribute($attribute);

	/**
	 * Writes an attribute value by its name
	 * 
	 * @param string $attribute
	 * @param mixed $value
	 *
	 *
	 * @return void
	 */
	public function writeAttribute($attribute, $value);

}
