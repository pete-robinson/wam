<?php
/*
 * This file is part of the Wam Web Asset Manager Package
 *
 * (c) Pete Robinson <work@pete-robinson.co.uk>
 *
 * For the full copyright and license information, please view the LICENSE file
 */
 
namespace Wam\AssetBundle\Entity\Base;
use Wam\AssetBundle\Asset\Directory\Directory;
use Doctrine\ORM\EntityManager;

class AbstractEntity
{
	/**
	 * Entity Manager
	 * @var Doctrine\ORM\EntityManager
	 **/
	protected $em;

	/**
	 * Primary key
	 * @var string
	 **/
	protected $primaryKey;

	/**
	 * Primary field
	 * @var string
	 **/
	protected $primaryField;

	/**
	 * Entity
	 * @var mixed
	 **/
	protected $entity;

	/**
	 * Assets
	 * @var string
	 **/
	protected $assets = array();

	/**
	 * web root directory
	 * @var string
	 **/
	protected $rootDir;

	/**
	 * asset directory name
	 * @var string
	 **/
	protected $assetDir = 'assets/';


	/**
	 * Constructor
	 * @param Doctrine\ORM\EntityManager
	 * @param mixed $entity
	 * @return void
	 **/
	public function __construct(EntityManager $em, $entity, $root_dir)
	{
		$this->em = $em;
		$this->entity = $entity;
		$this->rootDir = $root_dir;

		$this->getPrimaryKey();
		$this->mapDirectories();
	}

	/**
	 * getPrimaryKey
	 *
	 * @return void
	 **/
	private function getPrimaryKey()
	{
		$meta = $this->em->getClassMetadata(get_class($this->entity));

		$this->primaryField = $meta->getSingleIdentifierFieldName();

		$method = 'get' . ucwords($this->primaryField);

		$this->primaryKey = $this->entity->$method();
	}

	/**
	 * Map Directories
	 *
	 * @return void
	 **/
	public function mapDirectories()
	{
		$this->assets = array();

		foreach($this->dirs as $dir) {
			$d = str_replace('{' . $this->primaryField . '}', $this->primaryKey, $dir);

			$this->assets[] = new Directory(basename($d), $this->assetDir . $d, $this->rootDir);
		}
	}

	/**
	 * Create Directories
	 *
	 * @return 
	 **/
	public function create()
	{
		$this->mapDirectories();
		
		foreach($this->assets as $asset) {
			if(!$asset->exists()) {
				$asset->create();
			}
		}
	}

	/**
	 * get Assets
	 *
	 * @return array
	 **/
	public function getAssets()
	{
		return $this->assets;
	}
	
	

}