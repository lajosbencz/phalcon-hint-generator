<?php

namespace Phalcon\Mvc\Micro;

interface MiddlewareInterface
{

	/**
	 * Calls the middleware
	 * 
	 * @param \Phalcon\Mvc\Micro $application
	 *
	 * @return void
	 */
	public function call(\Phalcon\Mvc\Micro $application);

}
