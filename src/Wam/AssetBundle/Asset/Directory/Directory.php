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
use Wam\AssetBundle\Asset\Base\WebAssetInterface;
use Wam\AssetBundle\Asset\File\File;

class Directory extends AbstractWebAsset implements WebAssetInterface
{

	/**
	 * returns true if the directory exists
	 * @return bool
	 **/
	public function exists()
	{
		return is_dir($this->getRootPath());
	}

	/**
	 * create directory
	 * @return void
	 **/
	public function create()
	{
		if(!$this->exists()) {
			mkdir($this->getRootPath(), 0777);
			chmod($this->getRootPath(), 0777);

			$this->resolvePaths();
		}
	}

	/**
	 * delete
	 * @return void
	 **/
	public function delete()
	{
		if($this->exists()) {
			$this->deleteDirectory($this->getRootPath());
		}
	}

	/**
	 * clean
	 * @param string $directory
	 * @return void
	 **/
	public function clean($directory = false)
	{
		$directory = ($directory) ? $directory : $this->getRootPath();

		$files = glob(realpath($directory) . '/*');

		if($files) {
			foreach($files as $file) {
				if(is_file($file)) {
					@unlink($file);
				}
			}
		}
	}

	/**
	 * delete directory
	 * @param string $directory
	 * @return void
	 **/
	private function deleteDirectory($directory)
	{
		$this->clean($directory);

		if(count(scandir($directory)) == 2) {
			// dir is empty... delete
			rmdir($directory);
		} else {
			$dirs = glob($directory . '/*', GLOB_ONLYDIR);
			foreach($dirs as $dir) {
				// clean and remove it
				$this->deleteDirectory($dir);
			}

			$this->deleteDirectory($directory);
		}
	}

	/**
	 * put file into directory
	 * @param Wam\AssetBundle\Asset\File\File
	 * @return void
	 **/
	public function put(File $file)
	{
		return copy($file->getRootPath(), $this->getRootPath() . '/' . $file->getName());
	}
	

}