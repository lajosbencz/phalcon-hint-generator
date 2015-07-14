<?php

namespace Phalcon\Mvc;

interface ViewInterface extends ViewBaseInterface
{

	/**
	 * Sets the layouts sub-directory. Must be a directory under the views directory. Depending of your platform, always add a trailing slash or backslash
	 * 
	 * @param string $layoutsDir
	 *
	 * @return void
	 */
	public function setLayoutsDir($layoutsDir);

	/**
	 * Gets the current layouts sub-directory
	 *
	 * @return string
	 */
	public function getLayoutsDir();

	/**
	 * Sets a partials sub-directory. Must be a directory under the views directory. Depending of your platform, always add a trailing slash or backslash
	 * 
	 * @param string $partialsDir
	 *
	 * @return void
	 */
	public function setPartialsDir($partialsDir);

	/**
	 * Gets the current partials sub-directory
	 *
	 * @return string
	 */
	public function getPartialsDir();

	/**
	 * Sets base path. Depending of your platform, always add a trailing slash or backslash
	 * 
	 * @param string $basePath
	 *
	 * @return void
	 */
	public function setBasePath($basePath);

	/**
	 * Sets the render level for the view
	 * 
	 * @param string $level
	 *
	 * @return void
	 */
	public function setRenderLevel($level);

	/**
	 * Sets default view name. Must be a file without extension in the views directory
	 * 
	 * @param string $viewPath
	 *
	 * @return void
	 */
	public function setMainView($viewPath);

	/**
	 * Returns the name of the main view
	 *
	 * @return string
	 */
	public function getMainView();

	/**
	 * Change the layout to be used instead of using the name of the latest controller name
	 * 
	 * @param string $layout
	 *
	 * @return void
	 */
	public function setLayout($layout);

	/**
	 * Returns the name of the main view
	 *
	 * @return string
	 */
	public function getLayout();

	/**
	 * Appends template before controller layout
	 * 
	 * @param string|array $templateBefore
	 *
	 *
	 * @return void
	 */
	public function setTemplateBefore($templateBefore);

	/**
	 * Resets any template before layouts
	 *
	 * @return void
	 */
	public function cleanTemplateBefore();

	/**
	 * Appends template after controller layout
	 * 
	 * @param string|array $templateAfter
	 *
	 *
	 * @return void
	 */
	public function setTemplateAfter($templateAfter);

	/**
	 * Resets any template before layouts
	 *
	 * @return void
	 */
	public function cleanTemplateAfter();

	/**
	 * Gets the name of the controller rendered
	 *
	 * @return string
	 */
	public function getControllerName();

	/**
	 * Gets the name of the action rendered
	 *
	 * @return string
	 */
	public function getActionName();

	/**
	 * Gets extra parameters of the action rendered
	 *
	 * @return array
	 */
	public function getParams();

	/**
	 * Starts rendering process enabling the output buffering
	 *
	 * @return void
	 */
	public function start();

	/**
	 * Register templating engines
	 * 
	 * @param array $engines
	 *
	 * @return void
	 */
	public function registerEngines(array $engines);

	/**
	 * Executes render process from dispatching data
	 * 
	 * @param string $controllerName
	 * @param string $actionName
	 * @param array $params
	 *
	 *
	 * @return void
	 */
	public function render($controllerName, $actionName, $params=null);

	/**
	 * Choose a view different to render than last-controller/last-action
	 * 
	 * @param string $renderView
	 *
	 * @return void
	 */
	public function pick($renderView);

	/**
	 * Finishes the render process by stopping the output buffering
	 *
	 * @return void
	 */
	public function finish();

	/**
	 * Returns the path of the view that is currently rendered
	 *
	 * @return string
	 */
	public function getActiveRenderPath();

	/**
	 * Disables the auto-rendering process
	 *
	 * @return void
	 */
	public function disable();

	/**
	 * Enables the auto-rendering process
	 *
	 * @return void
	 */
	public function enable();

	/**
	 * Resets the view component to its factory default values
	 *
	 * @return void
	 */
	public function reset();

	/**
	 * Whether the automatic rendering is disabled
	 *
	 * @return boolean
	 */
	public function isDisabled();

}
