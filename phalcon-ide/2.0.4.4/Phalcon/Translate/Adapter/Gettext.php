<?php

namespace Phalcon\Translate\Adapter;

use Phalcon\Translate\Exception;
use Phalcon\Translate\AdapterInterface;
use Phalcon\Translate\Adapter;


class Gettext extends Adapter implements AdapterInterface, \ArrayAccess
{

	/**
	 * @var string|array
	 */
	protected $_directory;

	/**
	 * @var string
	 */
	protected $_defaultDomain;

	/**
	 * @var string
	 */
	protected $_locale;

	/**
	 * @var int
	 */
	protected $_category;



	/**
	 * Phalcon\Translate\Adapter\Gettext constructor
	 * 
	 * @param array $options
	 */
	public function __construct(array $options) {}

	/**
	 * Returns the translation related to the given key
	 *
	 * @param string $index
	 * @param array $placeholders
	 * @param string $domain
	 * 
	 * @return string
	 */
	public function query($index, $placeholders=null) {}

	/**
	 * Check whether is defined a translation key in the internal array
	 * 
	 * @param string $index
	 *
	 * @return boolean
	 */
	public function exists($index) {}

	/**
	 * The plural version of gettext().
	 * Some languages have more than one form for plural messages dependent on the count.
	 *
	 *
	 * @param string $msgid1
	 * @param string $msgid2
	 * @param int $count
	 * @param array $placeholders
	 * @param string $domain
	 * 
	 * @return string
	 */
	public function nquery($msgid1, $msgid2, $count, $placeholders=null, $domain=null) {}

	/**
	 * Changes the current domain (i.e. the translation file). The passed domain must be one
	 * of those passed to the constructor.
	 *
	 *
	 * @param string $domain
	 * 
	 * @return string
	 .
	 * @throws \InvalidArgumentException
	 */
	public function setDomain($domain) {}

	/**
	 * Sets the default domain
	 *
	 * @return string
	 .
	 */
	public function resetDomain() {}

	/**
	 * Sets the domain default to search within when calls are made to gettext()
	 * 
	 * @param string $domain
	 *
	 * @return void
	 */
	public function setDefaultDomain($domain) {}

	/**
	 * Gets the default domain
	 *
	 * @return string
	 */
	public function getDefaultDomain() {}

	/**
	 * Sets the path for a domain
	 * 
	 * @param mixed $directory
	 *
	 * @return void
	 */
	public function setDirectory($directory) {}

	/**
	 * Gets the path for a domain
	 * 
	 * @param mixed $directory
	 *
	 * @return string|array
	 */
	public function getDirectory($directory) {}

	/**
	 * Sets locale information
	 * 
	 * @param int $category
	 * @param string $locale
	 *
	 * @return string|boolean
	 */
	public function setLocale($category, $locale) {}

	/**
	 * Gets locale
	 *
	 * @return string
	 */
	public function getLocale() {}

	/**
	 * Gets locale category
	 *
	 * @return int
	 */
	public function getCategory() {}

	/**
	 * Validator for constructor
	 * 
	 * @param array $options
	 *
	 * @return void
	 */
	protected function prepareOptions(array $options) {}

	/**
	 * Gets default options
	 *
	 * @return array
	 */
	protected function getOptionsDefault() {}

}
