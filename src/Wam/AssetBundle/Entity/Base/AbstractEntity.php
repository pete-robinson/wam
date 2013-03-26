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

abstract class AbstractEntity
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
		$arr = explode('/', $this->rootDir);
		$results = array_filter($arr, 'strlen');
		$web_root = end($results);
		
		foreach($this->dirs as $dir) {
			if(is_array($dir)) {
				$dir = $dir['path'];
			}

			$d = str_replace('//', '/', $web_root . '/' . $this->assetDir . '/' . str_replace('{' . $this->primaryField . '}', $this->primaryKey, $dir));
			

			$this->assets[] = new Directory($d);
		}
	}

	/**
	 * Create Directories
	 *
	 * @return 
	 **/
	public function create()
	{
		foreach($this->assets as $asset) {
			if(!$asset->exists()) {
				$asset->create();
			}
		}
	}

	/**
	 * get directories
	 * @return void
	 **/
	public function getDirs()
	{
		$this->mapDirectories();
		return $this->assets;
	}

	/**
	 * get sizes and directories
	 * @return void
	 **/
	public function getSizeDirs()
	{
		$return = array();
		$directories = $this->getDirs();

		foreach($this->dirs as $key => $dir) {
			$i = (is_array($dir)) ? $dir['path'] : $dir;

			if(is_numeric(substr($i, -1))) {
				$return[basename($i)] = array(
					'method' => (is_array($dir)) ? $dir['method'] : 'width',
					'directory' => $directories[$key]
				);
			}
		}

		return $return;
	}
	
	

}