<?php

namespace Phalcon\Session;

interface BagInterface
{

	/**
	 * Initializes the session bag. This method must not be called directly, the class calls it when its internal data is accesed
	 *
	 * @return void
	 */
	public function initialize();

	/**
	 * Destroyes the session bag
	 *
	 * @return void
	 */
	public function destroy();

	/**
	 * Setter of values
	 * 
	 * @param string $property
	 * @param string $value
	 *
	 *
	 * @return void
	 */
	public function set($property, $value);

	/**
	 * Getter of values
	 *
	 * @param string $property
	 * @param mixed $defaultValue
	 * 
	 * @return void
	 */
	public function get($property, $defaultValue=null);

	/**
	 * Isset property
	 * 
	 * @param string $property
	 *
	 * @return boolean
	 */
	public function has($property);

	/**
	 * Setter of values
	 * 
	 * @param string $property
	 * @param string $value
	 *
	 *
	 * @return void
	 */
	public function __set($property, $value);

	/**
	 * Getter of values
	 *
	 * @param string $property
	 * 
	 * @return void
	 */
	public function __get($property);

	/**
	 * Isset property
	 * 
	 * @param string $property
	 *
	 * @return boolean
	 */
	public function __isset($property);

}
