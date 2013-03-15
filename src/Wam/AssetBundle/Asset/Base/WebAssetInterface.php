<?php
/*
 * This file is part of the Wam Web Asset Manager Package
 *
 * (c) Pete Robinson <work@pete-robinson.co.uk>
 *
 * For the full copyright and license information, please view the LICENSE file
 */
 
namespace Wam\AssetBundle\Asset\Base;

interface WebAssetInterface
{
	/**
	 * returns true if the file/directory exists
	 * @return string
	 **/
	public function exists();

	/**
	 * delete the asset
	 * @return void
	 **/
	public function delete();

}