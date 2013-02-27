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
use Wam\AssetBundle\Asset\Base\WebAsset;
use Wam\AssetBundle\Asset\Directory\Directory;
use Wam\AssetBundle\Exception\WamException;

class File extends AbstractWebAsset implements WebAsset
{
	/**
	 * Destination directory
	 * @var Wam\Assetbundle\Asset\Directory\Directory Directory
	 **/
	protected $destination;

	/**
	 * is upload
	 * @var boolean
	 **/
	protected $isUpload = false;
	

	/**
	 * tostring
	 * @return string
	 **/
	public function __tostring()
	{
		return $this->getName();
	}

	/**
	 * exists
	 * @return boolean
	 **/
	public function exists()
	{
		return file_exists($this->getRealPath() . '/' . $this->getName());
	}

	/**
	 * create the file 
	 * @return void
	 **/
	public function create()
	{
		if(!$this->getDestination()) {
			throw new WamException('Destination not specified');
		}

		return $this->destination->put($this);
	}

	/**
	 * Delete file
	 * @return void
	 **/
	public function delete()
	{
		if($this->exists()) {
			@unlink($this->getRealPath() . '/' . $this->getName());
		}
	}

	/**
	 * setDestination
	 * @param Directory $directory
	 * @return void
	 **/
	public function setDestination(Directory $directory)
	{
		$this->destination = $directory;
	}

	/**
	 * getDestination
	 * @return Directory
	 **/
	public function getDestination()
	{
		return $this->destination;
	}

	/**
	 * isUpload
	 * @return bool
	 **/
	public function isUpload()
	{
		return $this->isUpload;
	}

	/**
	 * setIsUpload
	 * @param boolean $is_upload
	 * @return bool
	 **/
	public function setIsUpload($is_upload)
	{
		$this->isUpload = $is_upload;
	}
	
	

}