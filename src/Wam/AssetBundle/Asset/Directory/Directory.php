<?php
/*
 * This file is part of the Wam Web Asset Manager Package
 *
 * (c) Pete Robinson <work@pete-robinson.co.uk>
 *
 * For the full copyright and license information, please view the LICENSE file
 */
 
namespace Wam\AssetBundle\Asset\Directory;
use Wam\AssetBundle\Asset\Base\AbstractWebAsset;
use Wam\AssetBundle\Asset\Base\WebAsset;
use Wam\AssetBundle\Asset\File\File;

class Directory extends AbstractWebAsset implements WebAsset
{

	/**
	 * ToString method
	 * @return string - directory path relative to doc root ($this->path)
	 **/
	public function __tostring()
	{
		return $this->getPath();
	}

	/**
	 * returns true if the directory exists
	 * @return bool
	 **/
	public function exists()
	{
		return is_dir($this->getRealPath());
	}
	

	/**
	 * create directory
	 * @return void
	 **/
	public function create()
	{
		mkdir($this->getRealPath(), 0777);
		// nuke permissions
		chmod($this->getRealPath(), 0777);
	}

	/**
	 * delete the directory
	 * @return bool
	 **/
	public function delete()
	{
		// if the directory exists, clean it
		if($this->exists()) {
			$this->clean();
			// then delete it
			rmdir($this->getRealPath());
		}
		// return true if directory has been deleted
		return (is_dir($this->getRealPath())) ? false : true;
	}

	/**
	 * cleanDir
	 * @todo write tis method
	 * @return void
	 **/
	public function clean()
	{
		$files = glob($this->getRealPath() . '/*');
		if($files) {
			foreach($files as $file) {
				@unlink($file);
			}
		}
	}

	/**
	 * put file into directory
	 * @param WebAsset $asset
	 * @return File
	 **/
	public function put(WebAsset $asset)
	{
		if($asset->isUpload()) {
			move_uploaded_file($asset->getRealPath() . '/' . $asset->getName(), $this->getRealPath() . '/' . $asset->getName());
		} else {
			copy($asset->getRealPath() . '/' . $asset->getName(), $this->getRealPath() . '/' . $asset->getName());
		}

		return new File($asset->getName(), $this->getPath(), str_replace($this->getPath(), '', $this->getRealPath()));
	}
	

	/**
	 * list files
	 * @param string $name
	 * @return Wam\AssetBundle\Asset\File
	 **/
	public function listDir()
	{
		$files = glob($this->getRealPath() . '/*.*');
		if($files) {
			foreach($files as $file) {
				$this->files[] = new File(basename($file), $this->getPath(), str_replace($this->getPath(), '', dirname($file)));
			}
		}

		return $this->files;
	}
	


	
	

}