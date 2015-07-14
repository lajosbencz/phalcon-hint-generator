<?php

namespace Phalcon\Forms;

use Phalcon\Validation\MessageInterface;
use Phalcon\Validation\ValidatorInterface;
use Phalcon\Validation\Message\Group;


interface ElementInterface
{

	/**
	 * Sets the parent form to the element
	 *
	 * @param \Phalcon\Forms\Form $form
	 * 
	 * @return void
	 */
	public function setForm(\Phalcon\Forms\Form $form);

	/**
	 * Returns the parent form to the element
	 *
	 * @return void
	 */
	public function getForm();

	/**
	 * Sets the element's name
	 *
	 * @param string $name
	 * 
	 * @return void
	 */
	public function setName($name);

	/**
	 * Returns the element's name
	 *
	 * @return string
	 */
	public function getName();

	/**
	 * Sets the element's filters
	 *
	 * @param array|string $filters
	 * 
	 * @return void
	 */
	public function setFilters($filters);

	/**
	 * Adds a filter to current list of filters
	 *
	 * @param string $filter
	 * 
	 * @return void
	 */
	public function addFilter($filter);

	/**
	 * Returns the element's filters
	 *
	 * @return void
	 */
	public function getFilters();

	/**
	 * Adds a group of validators
	 *
	 * @param array $validators
	 * @param boolean $merge
	 * 
	 * @return void
	 */
	public function addValidators(array $validators, $merge=true);

	/**
	 * Adds a validator to the element
	 *
	 * @param ValidatorInterface $validator
	 * 
	 * @return void
	 */
	public function addValidator(ValidatorInterface $validator);

	/**
	 * Returns the validators registered for the element
	 *
	 * @return void
	 */
	public function getValidators();

	/**
	 * Returns an array of prepared attributes for Phalcon\Tag helpers
	 * according to the element's parameters
	 *
	 * @param array $attributes
	 * @param boolean $useChecked
	 * 
	 * @return void
	 */
	public function prepareAttributes($attributes=null, $useChecked=false);

	/**
	 * Sets a default attribute for the element
	 *
	 * @param string $attribute
	 * @param mixed $value
	 * 
	 * @return void
	 */
	public function setAttribute($attribute, $value);

	/**
	 * Returns the value of an attribute if present
	 *
	 * @param string $attribute
	 * @param mixed $defaultValue
	 * 
	 * @return void
	 */
	public function getAttribute($attribute, $defaultValue=null);

	/**
	 * Sets default attributes for the element
	 *
	 * @param array $attributes
	 * 
	 * @return void
	 */
	public function setAttributes(array $attributes);

	/**
	 * Returns the default attributes for the element
	 *
	 * @return array
	 */
	public function getAttributes();

	/**
	 * Sets an option for the element
	 *
	 * @param string $option
	 * @param mixed $value
	 * 
	 * @return void
	 */
	public function setUserOption($option, $value);

	/**
	 * Returns the value of an option if present
	 *
	 * @param string $option
	 * @param mixed $defaultValue
	 * 
	 * @return void
	 */
	public function getUserOption($option, $defaultValue=null);

	/**
	 * Sets options for the element
	 *
	 * @param array $options
	 * 
	 * @return void
	 */
	public function setUserOptions($options);

	/**
	 * Returns the options for the element
	 *
	 * @return void
	 */
	public function getUserOptions();

	/**
	 * Sets the element label
	 *
	 * @param string $label
	 * 
	 * @return void
	 */
	public function setLabel($label);

	/**
	 * Returns the element's label
	 *
	 * @return string
	 */
	public function getLabel();

	/**
	 * Generate the HTML to label the element
	 *
	 * @return string
	 */
	public function label();

	/**
	 * Sets a default value in case the form does not use an entity
	 * or there is no value available for the element in _POST
	 *
	 * @param mixed $value
	 * 
	 * @return void
	 */
	public function setDefault($value);

	/**
	 * Returns the default value assigned to the element
	 *
	 * @return void
	 */
	public function getDefault();

	/**
	 * Returns the element's value
	 *
	 * @return void
	 */
	public function getValue();

	/**
	 * Returns the messages that belongs to the element
	 * The element needs to be attached to a form
	 *
	 * @return void
	 */
	public function getMessages();

	/**
	 * Checks whether there are messages attached to the element
	 *
	 * @return boolean
	 */
	public function hasMessages();

	/**
	 * Sets the validation messages related to the element
	 *
	 * @param Group $group
	 * 
	 * @return void
	 */
	public function setMessages(Group $group);

	/**
	 * Appends a message to the internal message list
	 *
	 * @param MessageInterface $message
	 * 
	 * @return void
	 */
	public function appendMessage(MessageInterface $message);

	/**
	 * Clears every element in the form to its default value
	 *
	 * @return void
	 */
	public function clear();

	/**
	 * Renders the element widget
	 *
	 * @param array $attributes
	 * 
	 * @return void
	 */
	public function render($attributes=null);

}
