<?php
/*
 * This file is part of the Wam Web Asset Manager Package
 *
 * (c) Pete Robinson <work@pete-robinson.co.uk>
 *
 * For the full copyright and license information, please view the LICENSE file
 */

namespace Wam\AssetBundle\Asset\File;
use Wam\AssetBundle\Asset\Base\AbstractWebAsset;
use Wam\AssetBundle\Asset\Base\WebAssetInterface;
use Wam\AssetBundle\Asset\Directory\Directory;
use Wam\AssetBundle\Exception\WamException;

class File extends AbstractWebAsset implements WebAssetInterface
{
	/**
	 * destination
	 * @var Wam\AssetBundle\Asset\Directory\Directory
	 **/
	private $destination;

	/**
	 * is upload
	 * @var boolean
	 **/
	private $isUpload = false;
	
	/**
	 * exists
	 * @return boolean
	 **/
	public function exists()
	{
		return file_exists($this->getRootPath());
	}

	/**
	 * create the file 
	 * @return void
	 **/
	public function create()
	{
		if($this->destination) {
			$this->destination->put($this);
			$this->setRootPath($this->getDestination()->getWebPath() . '/' . $this->getName());
		} else {
			throw new WamException('Directory not specified when moving file');
		}
	}

	/**
	 * Delete file
	 * @return void
	 **/
	public function delete()
	{
		if($this->exists()) {
			@unlink($this->getRootPath());
		}
	}

	/**
	 * set destination
	 * @param Wam\AssetBundle\Asset\Directory\Directory
	 * @return void
	 **/
	public function setDestination(Directory $directory)
	{
		$this->destination = $directory;
	}

	/**
	 * get destination
	 * @return Wam\AssetBundle\Asset\Directory\Directory
	 **/
	public function getDestination()
	{
		return $this->destination;
	}

	/**
	 * set is upload
	 * @param bool $isUpload
	 * @return void
	 **/
	public function setIsUpload($isUpload)
	{
		$this->isUpload = $isUpload;
	}

	/**
	 * get is uploade
	 * @return boolean
	 **/
	public function getIsUpload()
	{
		return $this->isUpload;
	}
	
	
	

}