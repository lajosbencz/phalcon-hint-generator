<?php

namespace Phalcon\Annotations;

use Phalcon\Annotations\ReaderInterface;


interface AdapterInterface
{

	/**
	 * Sets the annotations parser
	 * 
	 * @param ReaderInterface $reader
	 *
	 * @return void
	 */
	public function setReader(ReaderInterface $reader);

	/**
	 * Returns the annotation reader
	 *
	 * @return ReaderInterface
	 */
	public function getReader();

	/**
	 * Parses or retrieves all the annotations found in a class
	 *
	 * @param string|object $className
	 * 
	 * @return void
	 */
	public function get($className);

	/**
	 * Returns the annotations found in all the class' methods
	 *
	 * @param string $className
	 * 
	 * @return void
	 */
	public function getMethods($className);

	/**
	 * Returns the annotations found in a specific method
	 *
	 * @param string $className
	 * @param string $methodName
	 * 
	 * @return void
	 */
	public function getMethod($className, $methodName);

	/**
	 * Returns the annotations found in all the class' methods
	 *
	 * @param string $className
	 * 
	 * @return void
	 */
	public function getProperties($className);

	/**
	 * Returns the annotations found in a specific property
	 *
	 * @param string $className
	 * @param string $propertyName
	 * 
	 * @return void
	 */
	public function getProperty($className, $propertyName);

}
