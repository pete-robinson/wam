<?php
namespace Wam\AssetBundle\Asset\Base;

interface WebAsset
{

	/**
	 * template for getName()
	 * name of asset
	 * @return string
	 **/
	public function getName();

	/**
	 * template for getPath()
	 * Returns path relative to the doc root
	 * @param bool $real = false - set to true to return realpath
	 * @return string
	 **/
	public function getPath($rel = false);

	/**
	 * template for getRealPath()
	 * Returns the path from the server root
	 * @return string
	 **/
	public function getRealPath();

	/**
	 * template for create()
	 * creates asset
	 * @return void
	 **/
	public function create();
	
	/**
	 * template for delete()
	 * removes asset
	 * @return void
	 **/
	public function delete();
	
	
	
	

}