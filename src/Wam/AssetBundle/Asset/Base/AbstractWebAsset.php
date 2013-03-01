<?php
/*
 * This file is part of the Wam Web Asset Manager Package
 *
 * (c) Pete Robinson <work@pete-robinson.co.uk>
 *
 * For the full copyright and license information, please view the LICENSE file
 */
 
namespace Wam\AssetBundle\Asset\Base;

class AbstractWebAsset
{

	/**
	 * name of the directory
	 * @var string
	 **/
	protected $name;

	/**
	 * Path to the directory from doc root
	 * @var string
	 **/
	protected $path;

	/**
	 * path to the directory from server root
	 * @var string
	 **/
	protected $realPath;

	/**
	 * constructor - accepts arguments to allow for one-line configuration
	 * @param string $name - name of the directory
	 * @param string $path - path of the directory (from doc root)
	 * @param string $real_path - path of the directory (from server root)
	 * @return void
	 **/
	public function __construct($name, $path, $real_path)
	{
		$this->setName($name);
		$this->setPath($path);

		$real_path = (substr($real_path, strlen($real_path)-1, 1) == '/') ? $real_path . $path : $real_path . '/' . $path;
		$this->setRealPath($real_path);
	}

	/**
	 * set name
	 * @param string $name
	 * @return Wam\AssetBundle\Asset\Directory
	 **/
	public function setName($name)
	{
		$this->name = $name;
		return $this;
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
	 * set path
	 * @param string $path
	 * @return Wam\AssetBundle\Asset\Directory
	 **/
	public function setPath($path)
	{
		$this->path = $path;
		return $this;
	}

	/**
	 * get path
	 * @return string
	 **/
	public function getPath($real = false)
	{
		return ($real) ? $this->getRealPath() : $this->path;
	}

	/**
	 * set real path
	 * @param string $real_path
	 * @return Wam\AssetBundle\Asset\Directory
	 **/
	public function setRealPath($real_path)
	{
		$this->realPath = $real_path;
		return $this;
	}

	/**
	 * get real path
	 * @return string
	 **/
	public function getRealPath()
	{
		return $this->realPath;
	}
	
	

}