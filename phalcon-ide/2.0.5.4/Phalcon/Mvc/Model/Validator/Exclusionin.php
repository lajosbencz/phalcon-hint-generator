<?php

namespace Phalcon\Mvc\Model\Validator;

use Phalcon\Mvc\EntityInterface;
use Phalcon\Mvc\Model\Validator;
use Phalcon\Mvc\Model\ValidatorInterface;
use Phalcon\Mvc\Model\Exception;


class Exclusionin extends Validator implements ValidatorInterface
{

	/**
	 * Executes the validator
	 * 
	 * @param EntityInterface $record
	 *
	 * @return boolean
	 */
	public function validate(EntityInterface $record) {}

}
