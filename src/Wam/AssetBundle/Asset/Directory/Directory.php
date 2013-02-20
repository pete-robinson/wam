<?php
namespace Wam\AssetBundle\Asset\Directory;
use Wam\AssetBundle\Asset\Base\WebAsset;

class Directory implements WebAsset
{

	/**
	 * name
	 * @var string
	 **/
	protected $name;

	/**
	 * Path
	 * @var string
	 **/
	protected $path;

	/**
	 * real Path
	 * @var string
	 **/
	protected $realPath;


	public function __construct($name, $path, $real_path)
	{
		$this->setName($name);
		$this->setPath($path);
		$this->setRealPath($real_path . $path);

	}

	/**
	 * setName
	 * @param string $name
	 * @return Wam\AssetBundle\Asset\Directory
	 **/
	public function setName($name)
	{
		$this->name = $name;
		return $this;
	}

	/**
	 * getName
	 * @return string
	 **/
	public function getName()
	{
		return $this->name;
	}

	/**
	 * setPath
	 * @param string $path
	 * @return Wam\AssetBundle\Asset\Directory
	 **/
	public function setPath($path)
	{
		$this->path = $path;
		return $this;
	}

	/**
	 * getPath
	 * @return string
	 **/
	public function getPath($real = false)
	{
		return ($real) ? $this->getRealPath() : $this->path;
	}

	/**
	 * setRealPath
	 * @param string $real_path
	 * @return Wam\AssetBundle\Asset\Directory
	 **/
	public function setRealPath($real_path)
	{
		$this->realPath = $real_path;
		return $this;
	}

	/**
	 * getRealPath
	 * @return string
	 **/
	public function getRealPath()
	{
		return $this->realPath;
	}

	/**
	 * exists
	 *
	 * @return bool
	 **/
	public function exists()
	{
		return is_dir($this->getRealPath());
	}
	

	/**
	 * save
	 * @return void
	 **/
	public function create()
	{
		mkdir($this->getRealPath(), 0777);
		chmod($this->getRealPath(), 0777);
	}

	/**
	 * delete
	 * @return bool
	 **/
	public function delete()
	{
		if(is_dir($this->getRealPath())) {
			$this->cleanDir();
			rmdir($this->getRealPath());
		}

		return (is_dir($this->getRealPath())) ? false : true;
	}

	/**
	 * cleanDir
	 * @return void
	 **/
	public function cleanDir()
	{
		
	}
	


	
	

}