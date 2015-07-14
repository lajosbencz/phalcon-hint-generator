<?php

namespace Phalcon;

interface EscaperInterface
{

	/**
	 * Sets the encoding to be used by the escaper
	 * 
	 * @param string $encoding
	 *
	 *
	 * @return void
	 */
	public function setEncoding($encoding);

	/**
	 * Returns the internal encoding used by the escaper
	 *
	 * @return void
	 */
	public function getEncoding();

	/**
     * Sets the HTML quoting type for htmlspecialchars
	 * 
	 * @param int $quoteType
     *
     *
	 * @return void
	 */
	public function setHtmlQuoteType($quoteType);

	/**
     * Escapes a HTML string
     *
	 * @param string $text
	 * 
     * @return void
	 */
	public function escapeHtml($text);

	/**
     * Escapes a HTML attribute string
     *
	 * @param string $text
	 * 
     * @return void
	 */
	public function escapeHtmlAttr($text);

	/**
	 * Escape CSS strings by replacing non-alphanumeric chars by their hexadecimal representation
	 *
	 * @param string $css
	 * 
	 * @return void
	 */
	public function escapeCss($css);

	/**
	 * Escape Javascript strings by replacing non-alphanumeric chars by their hexadecimal representation
	 *
	 * @param string $js
	 * 
	 * @return void
	 */
	public function escapeJs($js);

	/**
     * Escapes a URL. Internally uses rawurlencode
     *
	 * @param string $url
	 * 
     * @return void
	 */
	public function escapeUrl($url);

}
