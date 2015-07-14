<?php

namespace Phalcon\Mvc\Model;

use Phalcon\Mvc\ModelInterface;
use Phalcon\Mvc\Model\Transaction\ManagerInterface;


interface TransactionInterface
{

	/**
	 * Sets transaction manager related to the transaction
	 * 
	 * @param ManagerInterface $manager
	 *
	 * @return void
	 */
	public function setTransactionManager(ManagerInterface $manager);

	/**
	 * Starts the transaction
	 *
	 * @return void
	 */
	public function begin();

	/**
	 * Commits the transaction
	 *
	 * @return void
	 */
	public function commit();

	/**
	 * Rollbacks the transaction
	 *
	 * @param string $rollbackMessage
	 * @param \Phalcon\Mvc\ModelInterface $rollbackRecord
	 * 
	 * @return void
	 */
	public function rollback($rollbackMessage=null, $rollbackRecord=null);

	/**
	 * Returns connection related to transaction
	 *
	 * @return void
	 */
	public function getConnection();

	/**
	 * Sets if is a reused transaction or new once
	 * 
	 * @param boolean $isNew
	 *
	 *
	 * @return void
	 */
	public function setIsNewTransaction($isNew);

	/**
	 * Sets flag to rollback on abort the HTTP connection
	 * 
	 * @param boolean $rollbackOnAbort
	 *
	 *
	 * @return void
	 */
	public function setRollbackOnAbort($rollbackOnAbort);

	/**
	 * Checks whether transaction is managed by a transaction manager
	 *
	 * @return void
	 */
	public function isManaged();

	/**
	 * Returns validations messages from last save try
	 *
	 * @return void
	 */
	public function getMessages();

	/**
	 * Checks whether internal connection is under an active transaction
	 *
	 * @return void
	 */
	public function isValid();

	/**
	 * Sets object which generates rollback action
	 * 
	 * @param ModelInterface $record
	 *
	 * @return void
	 */
	public function setRollbackedRecord(ModelInterface $record);

}
