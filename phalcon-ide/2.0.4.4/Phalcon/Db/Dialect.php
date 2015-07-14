<?php

namespace Phalcon\Db;

abstract class Dialect implements DialectInterface
{

	protected $_escapeChar;

	protected $_customFunctions;



	/**
	 * Registers custom SQL functions
	 * 
	 * @param string $name
	 * @param callable $customFunction
	 *
	 * @return Dialect
	 */
	public function registerCustomFunction($name, callable $customFunction) {}

	/**
	 * Returns registered functions
	 *
	 * @return array
	 */
	public function getCustomFunctions() {}

	/**
	 * Escape identifiers
	 * 
	 * @param string $str
	 * @param string $escapeChar
	 *
	 * @return string
	 */
	public final function escape($str, $escapeChar=null) {}

	/**
	 * Generates the SQL for LIMIT clause
	 *
	 * <code>
	 *    $sql = $dialect->limit('SELECT * FROM robots', 10);
	 *    echo $sql; // SELECT * FROM robots LIMIT 10
	 *
	 *    $sql = $dialect->limit('SELECT * FROM robots', [10, 50]);
	 *    echo $sql; // SELECT * FROM robots LIMIT 10 OFFSET 50
	 * </code>
	 * 
	 * @param string $sqlQuery
	 * @param mixed $number
	 *
	 * @return string
	 */
	public function limit($sqlQuery, $number) {}

	/**
	 * Returns a SQL modified with a FOR UPDATE clause
	 *
	 *<code>
	 * $sql = $dialect->forUpdate('SELECT * FROM robots');
	 * echo $sql; // SELECT * FROM robots FOR UPDATE
	 *</code>
	 * 
	 * @param string $sqlQuery
	 *
	 * @return string
	 */
	public function forUpdate($sqlQuery) {}

	/**
	 * Returns a SQL modified with a LOCK IN SHARE MODE clause
	 *
	 *<code>
	 * $sql = $dialect->sharedLock('SELECT * FROM robots');
	 * echo $sql; // SELECT * FROM robots LOCK IN SHARE MODE
	 *</code>
	 * 
	 * @param string $sqlQuery
	 *
	 * @return string
	 */
	public function sharedLock($sqlQuery) {}

	/**
	 * Gets a list of columns with escaped identifiers
	 *
	 * <code>
	 *    echo $dialect->getColumnList(array('column1', 'column'));
	 * </code>
	 * 
	 * @param array $columnList
	 *
	 * @return string
	 */
	public final function getColumnList(array $columnList) {}

	/**
	 * Resolve Column expressions
	 * 
	 * @param mixed $column
	 *
	 * @return string
	 */
	public final function getSqlColumn($column) {}

	/**
			 * The index "0" is the column field
	 * 
	 * @param array $expression
	 * @param string $escapeChar
			 *
	 * @return string
	 */
	public function getSqlExpression(array $expression, $escapeChar=null) {}

	/**
			 * Resolve scalar column expressions
	 * 
	 * @param mixed $table
	 * @param string $escapeChar
			 *
	 * @return string
	 */
	public final function getSqlTable($table, $escapeChar=null) {}

	/**
			 * The index "0" is the table name
	 * 
	 * @param array $definition
			 *
	 * @return string
	 */
	public function select(array $definition) {}

	/**
		 * Resolve COLUMNS
		 *
	 * @return boolean
	 */
	public function supportsSavepoints() {}

	/**
	 * Checks whether the platform supports releasing savepoints.
	 *
	 * @return boolean
	 */
	public function supportsReleaseSavepoints() {}

	/**
	 * Generate SQL to create a new savepoint
	 * 
	 * @param string $name
	 *
	 * @return string
	 */
	public function createSavepoint($name) {}

	/**
	 * Generate SQL to release a savepoint
	 * 
	 * @param string $name
	 *
	 * @return string
	 */
	public function releaseSavepoint($name) {}

	/**
	 * Generate SQL to rollback a savepoint
	 * 
	 * @param string $name
	 *
	 * @return string
	 */
	public function rollbackSavepoint($name) {}

	/**
	 * Resolve Column expressions
	 * 
	 * @param array $expression
	 * @param string $escapeChar
	 *
	 * @return string
	 */
	protected final function getSqlExpressionScalar(array $expression, $escapeChar=null) {}

	/**
	 * Resolve object expressions
	 * 
	 * @param array $expression
	 * @param string $escapeChar
	 *
	 * @return string
	 */
	protected final function getSqlExpressionObject(array $expression, $escapeChar=null) {}

	/**
	 * Resolve qualified expressions
	 * 
	 * @param array $expression
	 * @param string $escapeChar
	 *
	 * @return string
	 */
	protected final function getSqlExpressionQualified(array $expression, $escapeChar=null) {}

	/**
		 * A domain could be a table/schema
	 * 
	 * @param array $expression
	 * @param string $escapeChar
		 *
	 * @return string
	 */
	protected final function getSqlExpressionBinaryOperations(array $expression, $escapeChar=null) {}

	/**
	 * Resolve unary operations expressions
	 * 
	 * @param array $expression
	 * @param string $escapeChar
	 *
	 * @return string
	 */
	protected final function getSqlExpressionUnaryOperations(array $expression, $escapeChar=null) {}

	/**
		 * Some unary operators use the left operand...
	 * 
	 * @param array $expression
	 * @param string $escapeChar
		 *
	 * @return string
	 */
	protected final function getSqlExpressionFunctionCall(array $expression, $escapeChar=null) {}

	/**
	 * Resolve Lists
	 * 
	 * @param array $expression
	 * @param string $escapeChar
	 *
	 * @return string
	 */
	protected final function getSqlExpressionList(array $expression, $escapeChar=null) {}

	/**
	 * Resolve *
	 * 
	 * @param array $expression
	 * @param string $escapeChar
	 *
	 * @return string
	 */
	protected final function getSqlExpressionAll(array $expression, $escapeChar=null) {}

	/**
	 * Resolve CAST of values
	 * 
	 * @param array $expression
	 * @param string $escapeChar
	 *
	 * @return string
	 */
	protected final function getSqlExpressionCastValue(array $expression, $escapeChar=null) {}

	/**
	 * Resolve CONVERT of values encodings
	 * 
	 * @param array $expression
	 * @param string $escapeChar
	 *
	 * @return string
	 */
	protected final function getSqlExpressionConvertValue(array $expression, $escapeChar=null) {}

	/**
	 * Resolve CASE expressions
	 * 
	 * @param array $expression
	 * @param string $escapeChar
	 *
	 * @return string
	 */
	protected final function getSqlExpressionCase(array $expression, $escapeChar=null) {}

	/**
	 * Resolve a FROM clause
	 * 
	 * @param mixed $expression
	 * @param string $escapeChar
	 *
	 * @return string
	 */
	protected final function getSqlExpressionFrom($expression, $escapeChar=null) {}

	/**
	 * Resolve a JOINs clause
	 * 
	 * @param mixed $expression
	 * @param string $escapeChar
	 *
	 * @return string
	 */
	protected final function getSqlExpressionJoins($expression, $escapeChar=null) {}

	/**
			 * Check if the join has conditions
	 * 
	 * @param mixed $expression
	 * @param string $escapeChar
			 *
	 * @return string
	 */
	protected final function getSqlExpressionWhere($expression, $escapeChar=null) {}

	/**
	 * Resolve a GROUP BY clause
	 * 
	 * @param mixed $expression
	 * @param string $escapeChar
	 *
	 * @return string
	 */
	protected final function getSqlExpressionGroupBy($expression, $escapeChar=null) {}

	/**
	 * Resolve a HAVING clause
	 * 
	 * @param mixed $expression
	 * @param string $escapeChar
	 *
	 * @return string
	 */
	protected final function getSqlExpressionHaving($expression, $escapeChar=null) {}

	/**
	 * Resolve a ORDER BY clause
	 * 
	 * @param mixed $expression
	 * @param string $escapeChar
	 *
	 * @return string
	 */
	protected final function getSqlExpressionOrderBy($expression, $escapeChar=null) {}

	/**
				 * In the numeric 1 position could be a ASC/DESC clause
	 * 
	 * @param mixed $expression
	 * @param string $escapeChar
				 *
	 * @return string
	 */
	protected final function getSqlExpressionLimit($expression, $escapeChar=null) {}

	/**
			 * Check for a OFFSET condition
	 * 
	 * @param string $qualified
	 * @param string $alias
			 *
	 * @return string
	 */
	protected function prepareColumnAlias($qualified, $alias=null) {}

	/**
	 * Prepares table for this RDBMS
	 * 
	 * @param string $table
	 * @param string $schema
	 * @param string $alias
	 * @param string $escapeChar
	 *
	 * @return string
	 */
	protected function prepareTable($table, $schema=null, $alias=null, $escapeChar=null) {}

	/**
		 * Schema
	 * 
	 * @param string $column
	 * @param string $domain
	 * @param string $escapeChar
		 *
	 * @return string
	 */
	protected function prepareQualified($column, $domain=null, $escapeChar=null) {}

}
