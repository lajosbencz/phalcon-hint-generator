<?php

namespace Phalcon\Mvc;

use Phalcon\DiInterface;
use Phalcon\Mvc\Model\TransactionInterface;
use Phalcon\Mvc\Model\MessageInterface;


interface ModelInterface
{

	/**
	 * Sets a transaction related to the Model instance
	 * 
	 * @param TransactionInterface $transaction
	 *
	 * @return ModelInterface
	 */
	public function setTransaction(TransactionInterface $transaction);

	/**
	 * Returns table name mapped in the model
	 *
	 * @return void
	 */
	public function getSource();

	/**
	 * Returns schema name where table mapped is located
	 *
	 * @return void
	 */
	public function getSchema();

	/**
	 * Sets both read/write connection services
	 * 
	 * @param string $connectionService
	 *
	 * @return void
	 */
	public function setConnectionService($connectionService);

	/**
	 * Sets the DependencyInjection connection service used to write data
	 * 
	 * @param string $connectionService
	 *
	 * @return void
	 */
	public function setWriteConnectionService($connectionService);

	/**
	 * Sets the DependencyInjection connection service used to read data
	 * 
	 * @param string $connectionService
	 *
	 * @return void
	 */
	public function setReadConnectionService($connectionService);

	/**
	 * Returns DependencyInjection connection service used to read data
	 *
	 * @return string
	 */
	public function getReadConnectionService();

	/**
	 * Returns DependencyInjection connection service used to write data
	 *
	 * @return string
	 */
	public function getWriteConnectionService();

	/**
	 * Gets internal database connection
	 *
	 * @return \Phalcon\Db\AdapterInterface
	 */
	public function getReadConnection();

	/**
	 * Gets internal database connection
	 *
	 * @return \Phalcon\Db\AdapterInterface
	 */
	public function getWriteConnection();

	/**
	 * Sets the dirty state of the object using one of the DIRTY_STATE_* constants
	 * 
	 * @param int $dirtyState
	 *
	 * @return \Phalcon\Mvc\ModelInterface
	 */
	public function setDirtyState($dirtyState);

	/**
	 * Returns one of the DIRTY_STATE_* constants telling if the record exists in the database or not
	 *
	 * @return int
	 */
	public function getDirtyState();

	/**
	 * Assigns values to a model from an array
	 *
	 * @param array $data
	 * @param mixed $dataColumnMap
	 * @param mixed $whiteList
	 * @param \Phalcon\Mvc\Model $object
	 * @param array $columnMap
	 * 
	 * @return void
	 */
	public function assign(array $data, $dataColumnMap=null, $whiteList=null);

	/**
	 * Assigns values to a model from an array returning a new model
	 *
	 * @param \Phalcon\Mvc\Model $base
	 * @param array $data
	 * @param mixed $columnMap
	 * @param int $dirtyState
	 * @param boolean $keepSnapshots
	 * 
	 * @return void
	 */
	public static function cloneResultMap($base, array $data, $columnMap, $dirtyState, $keepSnapshots=null);

	/**
	 * Assigns values to a model from an array returning a new model
	 *
	 * @param ModelInterface $base
	 * @param array $data
	 * @param int $dirtyState
	 * 
	 * @return void
	 */
	public static function cloneResult(ModelInterface $base, array $data, $dirtyState);

	/**
	 * Returns an hydrated result based on the data and the column map
	 * 
	 * @param array $data
	 * @param mixed $columnMap
	 * @param int $hydrationMode
	 *
	 *
	 * @return void
	 */
	public static function cloneResultMapHydrate(array $data, $columnMap, $hydrationMode);

	/**
	 * Allows to query a set of records that match the specified conditions
	 *
	 * @param array $parameters
	 * 
	 * @return void
	 */
	public static function find($parameters=null);

	/**
	 * Allows to query the first record that match the specified conditions
	 *
	 * @param array $parameters
	 * 
	 * @return void
	 */
	public static function findFirst($parameters=null);

	/**
	 * Create a criteria for a especific model
	 *
	 * @param DiInterface $dependencyInjector
	 * 
	 * @return void
	 */
	public static function query(DiInterface $dependencyInjector=null);

	/**
	 * Allows to count how many records match the specified conditions
	 *
	 * @param array $parameters
	 * 
	 * @return void
	 */
	public static function count($parameters=null);

	/**
	 * Allows to calculate a summatory on a column that match the specified conditions
	 *
	 * @param array $parameters
	 * 
	 * @return void
	 */
	public static function sum($parameters=null);

	/**
	 * Allows to get the maximum value of a column that match the specified conditions
	 *
	 * @param array $parameters
	 * 
	 * @return void
	 */
	public static function maximum($parameters=null);

	/**
	 * Allows to get the minimum value of a column that match the specified conditions
	 *
	 * @param array $parameters
	 * 
	 * @return void
	 */
	public static function minimum($parameters=null);

	/**
	 * Allows to calculate the average value on a column matching the specified conditions
	 *
	 * @param array $parameters
	 * 
	 * @return void
	 */
	public static function average($parameters=null);

	/**
	 * Fires an event, implicitly calls behaviors and listeners in the events manager are notified
	 *
	 * @param string $eventName
	 * 
	 * @return void
	 */
	public function fireEvent($eventName);

	/**
	 * Fires an event, implicitly calls behaviors and listeners in the events manager are notified
	 * This method stops if one of the callbacks/listeners returns boolean false
	 *
	 * @param string $eventName
	 * 
	 * @return void
	 */
	public function fireEventCancel($eventName);

	/**
	 * Appends a customized message on the validation process
	 * 
	 * @param MessageInterface $message
	 *
	 * @return void
	 */
	public function appendMessage(MessageInterface $message);

	/**
	 * Check whether validation process has generated any messages
	 *
	 * @return void
	 */
	public function validationHasFailed();

	/**
	 * Returns all the validation messages
	 *
	 * @return void
	 */
	public function getMessages();

	/**
	 * Inserts or updates a model instance. Returning true on success or false otherwise.
	 *
	 * @param array $data
	 * @param array $whiteList
	 * 
	 * @return void
	 */
	public function save($data=null, $whiteList=null);

	/**
	 * Inserts a model instance. If the instance already exists in the persistance it will throw an exception
	 * Returning true on success or false otherwise.
	 *
	 * @param array $data
	 * @param array $whiteList
	 * 
	 * @return void
	 */
	public function create($data=null, $whiteList=null);

	/**
	 * Updates a model instance. If the instance doesn't exist in the persistance it will throw an exception
	 * Returning true on success or false otherwise.
	 *
	 * @param array $data
	 * @param array $whiteList
	 * 
	 * @return void
	 */
	public function update($data=null, $whiteList=null);

	/**
	 * Deletes a model instance. Returning true on success or false otherwise.
	 *
	 * @return void
	 */
	public function delete();

	/**
	 * Returns the type of the latest operation performed by the ORM
	 * Returns one of the OP_* class constants
	 *
	 * @return void
	 */
	public function getOperationMade();

	/**
	 * Refreshes the model attributes re-querying the record from the database
	 *
	 * @return void
	 */
	public function refresh();

	/**
	 * Skips the current operation forcing a success state
	 * 
	 * @param boolean $skip
	 *
	 * @return void
	 */
	public function skipOperation($skip);

	/**
	 * Returns related records based on defined relations
	 *
	 * @param string $alias
	 * @param array $arguments
	 * 
	 * @return void
	 */
	public function getRelated($alias, $arguments=null);

	/**
	 * Sets the record's snapshot data.
	 * This method is used internally to set snapshot data when the model was set up to keep snapshot data
	 * 
	 * @param array $data
	 * @param array $columnMap
	 *
	 *
	 * @return void
	 */
	public function setSnapshotData(array $data, $columnMap=null);

	/**
	 * Reset a model instance data
	 *
	 * @return void
	 */
	public function reset();

}