<?php

namespace Phalcon\Image;

interface AdapterInterface
{

	/**
	 * 
	 * @param int $width
	 * @param int $height
	 * @param int $master
	 *
	 * @return void
	 */
	public function resize($width=null, $height=null, $master=Image::AUTO);

	/**
	 * 
	 * @param int $width
	 * @param int $height
	 * @param int $offsetX
	 * @param int $offsetY
	 *
	 * @return void
	 */
	public function crop($width, $height, $offsetX=null, $offsetY=null);

	/**
	 * 
	 * @param int $degrees
	 *
	 * @return void
	 */
	public function rotate($degrees);

	/**
	 * 
	 * @param int $direction
	 *
	 * @return void
	 */
	public function flip($direction);

	/**
	 * 
	 * @param int $amount
	 *
	 * @return void
	 */
	public function sharpen($amount);

	/**
	 * 
	 * @param int $height
	 * @param int $opacity
	 * @param boolean $fadeIn
	 *
	 * @return void
	 */
	public function reflection($height, $opacity=100, $fadeIn=false);

	/**
	 * 
	 * @param Adapter $watermark
	 * @param int $offsetX
	 * @param int $offsetY
	 * @param int $opacity
	 *
	 * @return void
	 */
	public function watermark(Adapter $watermark, $offsetX, $offsetY, $opacity=100);

	/**
	 * 
	 * @param string $text
	 * @param int $offsetX
	 * @param int $offsetY
	 * @param int $opacity
	 * @param string $color
	 * @param int $size
	 * @param string $fontfile
	 *
	 * @return void
	 */
	public function text($text, $offsetX, $offsetY, $opacity=100, $color="000000", $size=12, $fontfile=null);

	/**
	 * 
	 * @param Adapter $watermark
	 *
	 * @return void
	 */
	public function mask(Adapter $watermark);

	/**
	 * 
	 * @param string $color
	 * @param int $opacity
	 *
	 * @return void
	 */
	public function background($color, $opacity=100);

	/**
	 * 
	 * @param int $radius
	 *
	 * @return void
	 */
	public function blur($radius);

	/**
	 * 
	 * @param int $amount
	 *
	 * @return void
	 */
	public function pixelate($amount);

	/**
	 * 
	 * @param string $file
	 * @param int $quality
	 *
	 * @return void
	 */
	public function save($file=null, $quality=100);

	/**
	 * 
	 * @param string $ext
	 * @param int $quality
	 *
	 * @return void
	 */
	public function render($ext=null, $quality=100);

}
