<?php

namespace Phalcon\Mvc\Model;

interface MessageInterface
{

	/**
	 * Phalcon\Mvc\Model\Message constructor
	 * 
	 * @param string $message
	 * @param string $field
	 * @param string $type
	 *
	 */
	public function __construct($message, $field=null, $type=null);

	/**
	 * Sets message type
	 * 
	 * @param string $type
	 *
	 *
	 * @return void
	 */
	public function setType($type);

	/**
	 * Returns message type
	 *
	 * @return void
	 */
	public function getType();

	/**
	 * Sets verbose message
	 * 
	 * @param string $message
	 *
	 *
	 * @return void
	 */
	public function setMessage($message);

	/**
	 * Returns verbose message
	 *
	 * @return void
	 */
	public function getMessage();

	/**
	 * Sets field name related to message
	 * 
	 * @param string $field
	 *
	 *
	 * @return void
	 */
	public function setField($field);

	/**
	 * Returns field name related to message
	 *
	 * @return void
	 */
	public function getField();

	/**
	 * Magic __toString method returns verbose message
	 *
	 * @return string
	 */
	public function __toString();

	/**
	 * Magic __set_state helps to recover messsages from serialization
	 * 
	 * @param array $message
	 *
	 * @return MessageInterface
	 */
	public static function __set_state(array $message);

}
