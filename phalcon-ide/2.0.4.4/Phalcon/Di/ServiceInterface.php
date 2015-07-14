<?php

namespace Phalcon\Di;

interface ServiceInterface
{

	/**
	 * Phalcon\Di\ServiceInterface
	 * 
	 * @param string $name
	 * @param mixed $definition
	 * @param boolean $shared
	 *
	 */
	public function __construct($name, $definition, $shared=false);

	/**
	 * Returns the service's name
	 *
	 * @param string
	 *
	 * @return void
	 */
	public function getName();

	/**
	 * Sets if the service is shared or not
	 * 
	 * @param boolean $shared
	 *
	 * @return void
	 */
	public function setShared($shared);

	/**
	 * Check whether the service is shared or not
	 *
	 * @return boolean
	 */
	public function isShared();

	/**
	 * Set the service definition
	 * 
	 * @param mixed $definition
	 *
	 *
	 * @return void
	 */
	public function setDefinition($definition);

	/**
	 * Returns the service definition
	 *
	 * @return void
	 */
	public function getDefinition();

	/**
	 * Resolves the service
	 *
	 * @param array $parameters
	 * @param \Phalcon\DiInterface $dependencyInjector
	 * 
	 * @return void
	 */
	public function resolve($parameters=null, \Phalcon\DiInterface $dependencyInjector=null);

	/**
	 * Restore the interal state of a service
	 * 
	 * @param array $attributes
	 *
	 * @return ServiceInterface
	 */
	public static function __set_state(array $attributes);

}
