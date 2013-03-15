<?php
/*
 * This file is part of the Wam Web Asset Manager Package
 *
 * (c) Pete Robinson <work@pete-robinson.co.uk>
 *
 * For the full copyright and license information, please view the LICENSE file
 */

namespace Wam\AssetBundle\Common;

use Doctrine\Common\Annotations\AnnotationReader;
use Wam\AssetBundle\Container\AbstractContainerAware;
use Wam\AssetBundle\Annotations\Entity;
use Wam\AssetBundle\Annotations\Dirs;
use Wam\AssetBundle\Exception\WamException;
use Wam\AssetBundle\Entity\EntityCreator;

class Wam extends AbstractContainerAware
{

	/**
	 * Entity Manager
	 * @var Doctrine\ORM\EntityManager
	 **/
	private $em;

	/**
	 * entityPath
	 * @var string
	 **/
	private $entityPath;

	/**
	 * property
	 * @var array
	 **/
	private $property;

	/**
	 * class reflection
	 * @var \ReflectionClass
	 **/
	private $classReflection;

	/**
	 * Wam entity directory where WamEntity classes are stored
	 * @var string
	 **/
	private $wamEntityDir = 'WamEntity';

	/**
	 * web directory
	 * @var string
	 **/
	private $webDir;
	

	/**
	 * initialize
	 * @return void
	 **/
	public function initialize()
	{
		$this->em = $this->getContainer()->get('doctrine')->getManager();
	}

	/**
	 * load
	 * @param mixed $doctrine_entity
	 * @return void
	 **/
	public function load($doctrine_entity)
	{
		// get the document root
		$root_dir = $this->getContainer()->get('kernel')->getRootDir() . '/../web/';
		// get the class that's been request
		$class = $this->getEntityNamespace(get_class($doctrine_entity));

		// if class doesn't exist, throw an error
		if(!class_exists($class)) {
			throw new WamException('Wam Entity: ' . $class . ' does not exist');
		}

		// instantiate the child of WamAsset class
		$this->entity = new $class($this->em, $doctrine_entity, $root_dir);

		return $this->entity;
	}

	/**
	 * takes a path to an entity and formats it as a namespace
	 * @param string $path - (i.e. AcmeTestBundle:WamEntity:Product)
	 * @return void
	 **/
	private function getEntityNamespace($path)
	{
		// explode the namespace by slash
		$namespace_array = explode('\\', $path);

		// assign parts of the exploded array to variables
		$entity_name = end($namespace_array);
		$package = $namespace_array[0];
		$bundle = $namespace_array[1];

		// use the kernel to try and fetch the bundle namespace
		$bundle = $this->getContainer()->get('kernel')->getBundle($package . $bundle);

		// explode by slash
		$bundle_array = explode('\\', get_class($bundle));
		
		// remove Package and Bundle name from $namespace_array
		array_shift($namespace_array);

		// concatonate array elements, resulting in the namespace of the package and bundle name
		$class = $bundle_array[0] . '\\' . $bundle_array[1] . '\\' . $this->wamEntityDir . '\\' . end($namespace_array);

		return $class;
	}

	/**
	 * create a new entity
	 * @param string $namespace - namespace of the file we want to create WAM entities from
	 * @return void
	 **/
	public function create($namespace)
	{
		// check that the entity given is a valid namespace
		if($this->entityIsValid($namespace)) {
			// fetch the @Wam\Dirs property from the class
			$this->loadProperties();
			
			// create the wam asset directory
			$this->createAssetDirectory($namespace);

			// create a new Entity Creator, pass it the enitty path and instruct to create a new WamEntity
			$entity_creator = new EntityCreator();
			$entity_creator->setAssetPath($this->getEntityPath($namespace));
			return $entity_creator->create($namespace);
		} else {
			throw new WamException('Entity class must have a @Wam\Entity prior to class definition');
		}
	}

	/**
	 * entityIsValid
	 * @param string $entity
	 * @return boolean
	 **/
	protected function entityIsValid($entity)
	{
		// load annotation reader
		$annotation_reader = new AnnotationReader();

		if(!class_exists($entity)) {
			throw new WamException('Entity ' . $entity . ' does not exist!');
		}

		// reflect entity
		$this->classReflection = new \ReflectionClass($entity);
		// pass entity annotations to annotation reader for formatting
		$annotations = $annotation_reader->getClassAnnotations($this->classReflection);

		// loop through each annotation. if it contains @WAM\Entity, set flat to true
		$return = false;
		foreach($annotations as $annotation) {
			if($annotation instanceof Entity) {
				$return = true;
				break;
			}
		}

		return $return;
	}

	/**
	 * extract wam properties from Entity
	 * @return void
	 **/
	protected function loadProperties()
	{
		// fetch the reflected entities' properties
		$properties = $this->classReflection->getProperties();

		// boot up Doctrine's Annotation reader
		$annotation_reader = new AnnotationReader();

		// loop through each class property
		foreach($properties as $property) {
			// get annotations for that property
			$annotation = $annotation_reader->getPropertyAnnotations($property);
			// loop through each annotation
			foreach($annotation as $a) {
				// if it's an instance of Wam\AssetBundle\Annotations\Dirs,
				// store the property $this->property and stop the loops
				if($a instanceof Dirs) {
					$this->property = $property;
					break;
				}
			}

			if($this->property) {
				break;
			}
		}

		// if a property wasn't found, throw an exception
		if(!$this->property) {
			throw new WamException('Could not locate @Wam\Dir in any class properties');
		}
	}
	
	
	/**
	 * createAssetDirectory
	 * if WamAsset directory for user specificed bundle does not exist, create it
	 * @param string $namespace - namespace of the entity to be used to create a WamAsset class
	 * @return void
	 **/
	private function createAssetDirectory($namespace)
	{
		$path = $this->getEntityPath($namespace);
		
		// create the directory if it does not exist
		if(!is_dir($path)) {
			mkdir($path, 0777);
			chmod($path, 0777);
		}
		
		// if the directory still doesn't exists, throw an error
		if(!is_dir($path) || !is_writable($path)) {
			throw new WamException('Could not create entity path. Check your bundle permissions');
		}

	}

	/**
	 * get entity path
	 * returns the path to the WamAsset directory
	 * @return string
	 **/
	public function getEntityPath($namespace)
	{
		if(!$this->entityPath) {
			// get the kernel
			$kernel = $this->getContainer()->get('kernel');
			// get the kernel rood dir
			$kernel_dir = $kernel->getRootDir();

			// get the list of installed bundles from the Kernel
			$bundles = $kernel->getBundles();

			// get the bundle and it's parent name from the user provided entify and combine them into one string
			$arr = explode('\\', $namespace);
			$bundle_name = $arr[0] . $arr[1];

			// get the bundle path by using $bundle_name as a key in the $bundles array
			$bundle_path = $bundles[$bundle_name]->getPath();

			// append WamEntity to the path
			$this->entityPath = $bundle_path . '/' . $this->wamEntityDir;
		}

		return $this->entityPath;
	}

}