<?php
/*
 * This file is part of the Wam Web Asset Manager Package
 *
 * (c) Pete Robinson <work@pete-robinson.co.uk>
 *
 * For the full copyright and license information, please view the LICENSE file
 */
 
namespace Wam\AssetBundle\Entity;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Wam\AssetBundle\Annotations\Dirs;
use Wam\AssetBundle\Entity\Property\Property;
use Wam\AssetBundle\Entity\Entity;


class EntityCreator
{

	/**
	 * assetPath
	 * @param string
	 **/
	protected $assetPath;

	/**
	 * namespace of origin Entity
	 * @var string
	 **/
	protected $entity;

	/**
	 * dirs
	 * Directories to be stored in the WamEntity
	 * @var string
	 **/
	protected $dirs;

	/**
	 * Created entity
	 * @var Wam\AssetBundle\Entity\Entity
	 **/
	protected $createdEntity;

	/**
	 * setAssetPath
	 * @param string $asset_path
	 * @return void
	 **/
	public function setAssetPath($path)
	{
		$this->assetPath = $path;
	}

	/**
	 * getAssetPath
	 * @return string
	 **/
	public function getAssetPath()
	{
		return $this->assetPath;
	}

	/**
	 * getDirs
	 * @return array
	 **/
	public function getDirs()
	{
		return $this->dirs;
	}

	/**
	 * create
	 * @param string $entity - namespace of the entity to create an Entity for
	 * @return void
	 **/
	public function create($entity)
	{
		$this->entity = $entity;
		$this->dirs = $this->extractDirs();
		
		$this->createdEntity = new Entity($this->getEntityName());
		$this->createdEntity->setDestinationFile($this->getAssetPath())
			->setExtends('AbstractEntity')
			->addUses('Wam\AssetBundle\Entity\Base\AbstractEntity')
			->setPermission('final')
			->addProperty(new Property('protected', 'dirs', $this->dirs))
			->compile();
		
		$this->createdEntity->save();
		
		return $this->createdEntity->getNamespace(true);
	}

	/**
	 * get created entity
	 * @return Wam\AssetBundle\Entity\Base\AbstractEntity
	 **/
	public function getCreatedEntity()
	{
		return $this->createdEntity->getNamespace(true);
	}

	/**
	 * extractDirs
	 * Reflects the entity to find the property flagged as the asset dirs
	 * @return void
	 **/
	private function extractDirs()
	{
		// init AnnotationReader
		$annotation_reader = new AnnotationReader();

		// reflect the class to find the properties
		$class_reflection = new \ReflectionClass($this->entity);

		$properties = $class_reflection->getProperties();

		$dirs = array();
		// loop through each property
		foreach($properties as $property) {
			// refleft the property
			$property_reflection = new \ReflectionProperty($this->entity, $property->getName());
			// pass annotations to annotation reader to format
			$annotations = $annotation_reader->getPropertyAnnotations($property_reflection);

			foreach($annotations as $annotation) {
				if($annotation instanceof Dirs) {
					$property_array = $class_reflection->getDefaultProperties();
					$dirs = $property_array[$property->getName()];
					break;
				}
			}

			if($dirs) {
				break;
			}
		}

		return $dirs;
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 **/
	protected function getEntityName()
	{
		$arr = explode('\\', $this->entity);
		return array_pop($arr);
	}
	
	


}