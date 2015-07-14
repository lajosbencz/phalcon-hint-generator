<?php

namespace Phalcon\Mvc\Model\Query;

interface BuilderInterface
{

	/**
	 * Phalcon\Mvc\Model\Query\Builder
	 * 
	 * @param array $params
	 *
	 */
	public function __construct($params=null);

	/**
	 * Sets the columns to be queried
	 *
	 * @param string|array $columns
	 * 
	 * @return void
	 */
	public function columns($columns);

	/**
	 * Return the columns to be queried
	 *
	 * @return void
	 */
	public function getColumns();

	/**
	 * Sets the models who makes part of the query
	 *
	 * @param string|array $models
	 * 
	 * @return void
	 */
	public function from($models);

	/**
	 * Add a model to take part of the query
	 *
	 * @param string $model
	 * @param string $alias
	 * 
	 * @return void
	 */
	public function addFrom($model, $alias=null);

	/**
	 * Return the models who makes part of the query
	 *
	 * @return void
	 */
	public function getFrom();

	/**
	 * Adds a INNER join to the query
	 *
	 * @param string $model
	 * @param string $conditions
	 * @param string $alias
	 * 
	 * @return void
	 */
	public function join($model, $conditions=null, $alias=null);

	/**
	 * Adds a INNER join to the query
	 *
	 * @param string $model
	 * @param string $conditions
	 * @param string $alias
	 * @param string $type
	 * 
	 * @return void
	 */
	public function innerJoin($model, $conditions=null, $alias=null);

	/**
	 * Adds a LEFT join to the query
	 *
	 * @param string $model
	 * @param string $conditions
	 * @param string $alias
	 * 
	 * @return void
	 */
	public function leftJoin($model, $conditions=null, $alias=null);

	/**
	 * Adds a RIGHT join to the query
	 *
	 * @param string $model
	 * @param string $conditions
	 * @param string $alias
	 * 
	 * @return void
	 */
	public function rightJoin($model, $conditions=null, $alias=null);

	/**
	 * Sets conditions for the query
	 *
	 * @param string $conditions
	 * @param array $bindParams
	 * @param array $bindTypes
	 * 
	 * @return void
	 */
	public function where($conditions, $bindParams=null, $bindTypes=null);

	/**
	 * Appends a condition to the current conditions using a AND operator
	 *
	 * @param string $conditions
	 * @param array $bindParams
	 * @param array $bindTypes
	 * 
	 * @return void
	 */
	public function andWhere($conditions, $bindParams=null, $bindTypes=null);

	/**
	 * Appends a condition to the current conditions using a OR operator
	 *
	 * @param string $conditions
	 * @param array $bindParams
	 * @param array $bindTypes
	 * 
	 * @return void
	 */
	public function orWhere($conditions, $bindParams=null, $bindTypes=null);

	/**
	 * Appends a BETWEEN condition to the current conditions
	 *
	 * @param string $expr
	 * @param mixed $minimum
	 * @param mixed $maximum
	 * 
	 * @return void
	 */
	public function betweenWhere($expr, $minimum, $maximum);

	/**
	 * Appends a NOT BETWEEN condition to the current conditions
	 *
	 * @param string $expr
	 * @param mixed $minimum
	 * @param mixed $maximum
	 * 
	 * @return void
	 */
	public function notBetweenWhere($expr, $minimum, $maximum);

	/**
	 * Appends an IN condition to the current conditions
	 * 
	 * @param string $expr
	 * @param array $values
	 *
	 * @return BuilderInterface
	 */
	public function inWhere($expr, array $values);

	/**
	 * Appends a NOT IN condition to the current conditions
	 * 
	 * @param string $expr
	 * @param array $values
	 *
	 * @return BuilderInterface
	 */
	public function notInWhere($expr, array $values);

	/**
	 * Return the conditions for the query
	 *
	 * @return void
	 */
	public function getWhere();

	/**
	 * Sets a ORDER BY condition clause
	 *
	 * @param string $orderBy
	 * 
	 * @return void
	 */
	public function orderBy($orderBy);

	/**
	 * Return the set ORDER BY clause
	 *
	 * @return void
	 */
	public function getOrderBy();

	/**
	 * Sets a HAVING condition clause
	 *
	 * @param string $having
	 * 
	 * @return void
	 */
	public function having($having);

	/**
	 * Returns the HAVING condition clause
	 *
	 * @return void
	 */
	public function getHaving();

	/**
	 * Sets a LIMIT clause
	 *
	 * @param int $limit
	 * @param int $offset
	 * 
	 * @return void
	 */
	public function limit($limit, $offset=null);

	/**
	 * Returns the current LIMIT clause
	 *
	 * @return void
	 */
	public function getLimit();

	/**
	 * Sets a LIMIT clause
	 *
	 * @param string $group
	 * 
	 * @return void
	 */
	public function groupBy($group);

	/**
	 * Returns the GROUP BY clause
	 *
	 * @return void
	 */
	public function getGroupBy();

	/**
	 * Returns a PHQL statement built based on the builder parameters
	 *
	 * @return void
	 */
	public function getPhql();

	/**
	 * Returns the query built
	 *
	 * @return void
	 */
	public function getQuery();

}
