<?php

namespace Phalcon\Mvc\Model;

interface ResultInterface
{

	/**
	 * Sets the object's state
	 * 
	 * @param boolean $dirtyState
	 *
	 *
	 * @return void
	 */
	public function setDirtyState($dirtyState);

}
