<?php
/*
 * This file is part of the Wam Web Asset Manager Package
 *
 * (c) Pete Robinson <work@pete-robinson.co.uk>
 *
 * For the full copyright and license information, please view the LICENSE file
 */
 
namespace Wam\AssetBundle\Asset\Base;

interface WebAsset
{

	/**
	 * tostring method
	 * @return string
	 **/
	public function __tostring();
	

	/**
	 * returns true if the directory exists
	 * @return string
	 **/
	public function exists();

	/**
	 * create asset
	 * @return void
	 **/
	public function create();

	/**
	 * deletes the asset
	 * @return void
	 **/
	public function delete();

}