<?php
/*
 * This file is part of the Wam Web Asset Manager Package
 *
 * (c) Pete Robinson <work@pete-robinson.co.uk>
 *
 * For the full copyright and license information, please view the LICENSE file
 */
 
namespace Wam\AssetBundle\Asset\Base;

abstract class AbstractWebAsset
{

	/**
	 * name of the directory
	 * @var string
	 **/
	protected $name;

	/**
	 * Path to the directory from server root
	 * @var string
	 **/
	protected $rootPath;

	/**
	 * path to the directory from web root
	 * @var string
	 **/
	protected $webPath;

	/**
	 * constructor
	 * @param string $path
	 * @return void
	 **/
	public function __construct($path)
	{
		$this->storeValues($path);
	}

	/**
	 * store values
	 * @param string $path
	 * @return void
	 **/
	public function storeValues($path)
	{
		$this->setName(basename($path));
		$this->setRootPath($path);
		$this->setWebPath($this->getRootPath());
	}

	/**
	 * set name
	 * @param string $name
	 * @return void
	 **/
	public function setName($name)
	{
		$this->name = $name;
	}

	/**
	 * get name
	 * @return string
	 **/
	public function getName()
	{
		return $this->name;
	}

	/**
	 * set root path
	 * @param string $rootPath
	 * @return void
	 **/
	public function setRootPath($rootPath)
	{
		$this->rootPath = realpath($_SERVER['KERNEL_ROOT_PATH']) . '/' . $rootPath;
	}

	/**
	 * get root path
	 * @return string
	 **/
	public function getRootPath()
	{
		return $this->rootPath;
	}

	/**
	 * set web path
	 * @param string $webPath
	 * @return void
	 **/
	public function setWebPath($webPath)
	{
		if($this->exists()) {
			if(isset($_SERVER['KERNEL_ROOT_PATH']) && $_SERVER['KERNEL_ROOT_PATH'] != '') {
				if(realpath($webPath) && strpos(realpath($webPath), $_SERVER['KERNEL_ROOT_PATH']) !== false) {
					$this->webPath = str_replace($_SERVER['KERNEL_ROOT_PATH'], '', $webPath);
				}
			}
		}
	}

	/**
	 * resolve path
	 * @return void
	 **/
	public function resolvePaths()
	{
		$this->setWebPath($this->getRootPath());
	}
	

	/**
	 * get web path
	 * @return string
	 **/
	public function getWebPath()
	{
		return $this->webPath;
	}

	/**
	 * get parent dir
	 * @return string
	 **/
	public function getParentDir()
	{
		if($this->exists()) {
			return dirname($this->getRootPath());
		}
	}
	
	
	

}