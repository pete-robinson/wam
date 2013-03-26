<?php
/*
 * This file is part of the Wam Web Asset Manager Package
 *
 * (c) Pete Robinson <work@pete-robinson.co.uk>
 *
 * For the full copyright and license information, please view the LICENSE file
 */
 
require_once __DIR__ . '/../WamTestCase.php';

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Wam\AssetBundle\Annotations\Dirs;
use Wam\AssetBundle\Entity\EntityCreator;

class EntityCreatorTest extends WamTestCase
{
	/**
	 * Entity Creator
	 * @var Wam\AssetBundle\Entity\EntityCreator;
	 **/
	private $ec;

	/**
	 * assetPath
	 * @var string
	 **/
	private $assetPath = '/../SupportFiles/src/Acme/TestBundle/WamEntity';

	/**
	 * undocumented class variable
	 *
	 * @var string
	 **/
	private $entityNamespace = 'Acme\TestBundle\Entity\Product';

	/**
	 * setup
	 * @return void
	 **/
	public function setUp()
	{
		parent::setUp();
		$this->getEntityCreator();
	}

	/**
	 * test Create
	 * @return void
	 **/
	public function testCreate()
	{
		$this->assertEquals($this->ec->getAssetPath(), __DIR__ . $this->assetPath);
		$this->assertEquals($this->ec->create($this->entityNamespace), 'Acme\TestBundle\WamEntity\Product');
	}

	/**
	 * test extract dirs
	 * @return void
	 **/
	public function testExtractDirs()
	{
		// init AnnotationReader
		$annotation_reader = new AnnotationReader();

		// reflect the class to find the properties
		$class_reflection = new \ReflectionClass($this->entityNamespace);

		$properties = $class_reflection->getProperties();

		$dirs = array();
		// loop through each property
		foreach($properties as $property) {
			// refleft the property
			$property_reflection = new \ReflectionProperty($this->entityNamespace, $property->getName());
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

		$this->assertEquals($this->ec->getDirs(), $dirs);
	}

	/**
	 * test get dirs
	 * @return void
	 **/
	public function testGetSizes()
	{
		$product = $this->em->getRepository('AcmeTestBundle:Product')->find(1);
		$entity = $this->container->get('wam')->load($product);
		
		$sizes = $entity->getSizeDirs();

		$this->assertTrue(array_key_exists('100', $sizes));
		$this->assertTrue(array_key_exists('200', $sizes));
		$this->assertTrue(array_key_exists('800', $sizes));

		$this->assertEquals($sizes[100]['method'], 'height');

		foreach($sizes as $key => $size) {
			$this->assertInternalType('integer', $key);
			$this->assertTrue(array_key_exists('directory', $size));
			$this->assertInstanceOf('Wam\AssetBundle\Asset\Directory\Directory', $size['directory']);
		}
	}

	/**
	 * test get created entity returns an instance of Newly Created Entity
	 * @return void
	 **/
	public function testGetCreatedEntityReturnsInstanceOfNewlyCreatedEntity()
	{
		$ec = $this->getEntityCreator();
		$this->assertEquals('Acme\TestBundle\WamEntity\Product', $ec->getCreatedEntity());
	}

	/**
	 * test add interface to entity
	 * @return void
	 **/
	public function testAddInterfaceToEntity()
	{
		$entity = new Wam\AssetBundle\Entity\Entity('test');
		$entity->setImplements('Acme\TestBundle\TestClass');
		$entity->compile();

		$this->assertContains('implements Acme\TestBundle\TestClass', $entity->getClassString());
	}
	
	
	

	/**
	 * get entity creator
	 * @return Wam\AssetBundle\Entity\EntityCreator;
	 **/
	private function getEntityCreator()
	{
		if(!$this->ec) {
			$this->ec = new EntityCreator();

			$this->ec->setAssetPath(__DIR__ . $this->assetPath);
			$this->ec->create($this->entityNamespace);
		}

		return $this->ec;
	}
	
	
	
	

}