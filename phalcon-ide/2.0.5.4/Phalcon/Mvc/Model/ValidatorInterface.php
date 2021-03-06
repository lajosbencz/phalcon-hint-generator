<?php

namespace Phalcon\Mvc\Model;

use Phalcon\Mvc\EntityInterface;


interface ValidatorInterface
{

	/**
	 * Returns messages generated by the validator
	 *
	 * @return array
	 */
	public function getMessages();

	/**
	 * Executes the validator
	 *
	 * @param EntityInterface $record
	 * 
	 * @return boolean
	 */
	public function validate(EntityInterface $record);

}
