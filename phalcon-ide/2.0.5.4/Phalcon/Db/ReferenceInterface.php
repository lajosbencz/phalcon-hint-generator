<?php

namespace Phalcon\Db;

interface ReferenceInterface
{

	/**
	 * Phalcon\Db\ReferenceInterface constructor
	 * 
	 * @param string $referenceName
	 * @param array $definition
	 */
	public function __construct($referenceName, array $definition);

	/**
	 * Gets the index name
	 *
	 * @return void
	 */
	public function getName();

	/**
	 * Gets the schema where referenced table is
	 *
	 * @return void
	 */
	public function getSchemaName();

	/**
	 * Gets the schema where referenced table is
	 *
	 * @return void
	 */
	public function getReferencedSchema();

	/**
	 * Gets local columns which reference is based
	 *
	 * @return void
	 */
	public function getColumns();

	/**
	 * Gets the referenced table
	 *
	 * @return void
	 */
	public function getReferencedTable();

	/**
	 * Gets referenced columns
	 *
	 * @return void
	 */
	public function getReferencedColumns();

	/**
	 * Gets the referenced on delete
	 *
	 * @return void
	 */
	public function getOnDelete();

	/**
	 * Gets the referenced on update
	 *
	 * @return void
	 */
	public function getOnUpdate();

	/**
	 * Restore a Phalcon\Db\Reference object from export
	 * 
	 * @param array $data
	 *
	 * @return ReferenceInterface
	 */
	public static function __set_state(array $data);

}
