<?php

namespace Phalcon\Mvc;

use Phalcon\DiInterface;


interface ModuleDefinitionInterface
{

	/**
	 * Registers an autoloader related to the module
	 * 
	 * @param DiInterface $dependencyInjector
	 *
	 * @return void
	 */
	public function registerAutoloaders(DiInterface $dependencyInjector=null);

	/**
	 * Registers services related to the module
	 * 
	 * @param DiInterface $dependencyInjector
	 *
	 * @return void
	 */
	public function registerServices(DiInterface $dependencyInjector);

}
