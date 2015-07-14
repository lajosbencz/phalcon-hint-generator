<?php

namespace Phalcon\Db;

interface ColumnInterface
{

	/**
	 * Phalcon\Db\ColumnInterface constructor
	 * 
	 * @param string $columnName
	 * @param array $definition
	 */
	public function __construct($columnName, array $definition);

	/**
	 * Returns schema's table related to column
	 *
	 * @return void
	 */
	public function getSchemaName();

	/**
	 * Returns column name
	 *
	 * @return void
	 */
	public function getName();

	/**
	 * Returns column type
	 *
	 * @return void
	 */
	public function getType();

	/**
	 * Returns column type reference
	 *
	 * @return void
	 */
	public function getTypeReference();

	/**
	 * Returns column type values
	 *
	 * @return void
	 */
	public function getTypeValues();

	/**
	 * Returns column size
	 *
	 * @return void
	 */
	public function getSize();

	/**
	 * Returns column scale
	 *
	 * @return void
	 */
	public function getScale();

	/**
	 * Returns true if number column is unsigned
	 *
	 * @return void
	 */
	public function isUnsigned();

	/**
	 * Not null
	 *
	 * @return void
	 */
	public function isNotNull();

	/**
	 * Column is part of the primary key?
	 *
	 * @return void
	 */
	public function isPrimary();

	/**
	 * Auto-Increment
	 *
	 * @return void
	 */
	public function isAutoIncrement();

	/**
	 * Check whether column have an numeric type
	 *
	 * @return void
	 */
	public function isNumeric();

	/**
	 * Check whether column have first position in table
	 *
	 * @return void
	 */
	public function isFirst();

	/**
	 * Check whether field absolute to position in table
	 *
	 * @return void
	 */
	public function getAfterPosition();

	/**
	 * Returns the type of bind handling
	 *
	 * @return void
	 */
	public function getBindType();

	/**
	 * Returns default value of column
	 *
	 * @return void
	 */
	public function getDefault();

	/**
	 * Restores the internal state of a Phalcon\Db\Column object
	 * 
	 * @param array $data
	 *
	 * @return ColumnInterface
	 */
	public static function __set_state(array $data);

}
