<?php
require_once __DIR__ . '/../WamTestCase.php';

use Wam\AssetBundle\Entity\Entity;
use Wam\AssetBundle\Entity\Property\Property;

class EntityTest extends WamTestCase
{

	public function setUp()
	{
		parent::setUp();
	}

	/**
	 * test get
	 *
	 * @return void
	 **/
	public function testGet()
	{
		$product = $this->getProduct(1);

		$wam = $this->container->get('wam')->getEntity('AcmeTestBundle:WamEntity:Product', $product);
		
		foreach(array_reverse($wam->getAssets()) as $dir) {
			if($dir->exists()) {
				$dir->delete();
			}
		}

		$wam->create();


	}

	/**
	 * @expectedException Wam\AssetBundle\Exception\WamException
	 * @return void
	 **/
	public function testError()
	{
		$product = $this->getProduct(1);

		$wam = $this->container->get('wam')->getEntity('AcmeTestBundle', $product);
	}

	/**
	 * get Product for test data
	 * @return Acme\TestBundle\Entity\Product
	 **/
	private function getProduct($id)
	{
		return $this->em->getRepository('AcmeTestBundle:Product')->find($id);
	}

	/**
	 * Test create
	 * @return void
	 **/
	public function testCreate()
	{
		$entity = $this->getStdEntity('Acme\TestBundle\WamEntity');
		
		$this->assertEquals($entity->getNamespace(), 'Acme\TestBundle\WamEntity');
		$this->assertEquals($entity->getExtends(), 'AbstractEntity');
		$this->assertEquals($entity->getImplements(), 'AssetDefinition');
		$this->assertTrue(is_array($entity->getProperties()));
		$this->assertEquals($entity->getName(), 'TestEntity');

		$uses = $entity->getUses();
		$this->assertEquals($uses[0], 'Wam\AssetBundle\Entity\Base\AbstractEntity');

		$properties = $entity->getProperties();
		$this->assertInstanceOf('Wam\AssetBundle\Entity\Property\Property', $properties[0]);
		
	}

	/**
	 * Test compile
	 * @return void
	 **/
	public function testCompile()
	{
		$entity = new Entity('TestEntity');
		$entity->setDestinationFile(__DIR__ . '/../SupportFiles/src/Acme/TestBundle/WamEntity')->compile();

		$this->assertFalse(strpos('extends', $entity->getClassString()));
		$this->assertFalse(strpos('implements', $entity->getClassString()));
		$this->assertFalse(strpos('use ', $entity->getClassString()));
		$this->assertFalse(strpos('public ', $entity->getClassString()));
		$this->assertFalse(strpos('private ', $entity->getClassString()));
		$this->assertFalse(strpos('protected ', $entity->getClassString()));
		
	}

	/**
	 * Test fail auto generate namespace
	 * @return void
	 **/
	public function testFailAutoGenerateNamespace()
	{
		$entity = $this->getStdEntity(false);
		$this->assertFalse($entity->autoGenerateNamespace());
	}
	

	/**
	 * get standard entity
	 *
	 * @return void
	 **/
	private function getStdEntity($namespace = false)
	{
		$dir = ($namespace !== false) ? __DIR__ . '/../SupportFiles/src/Acme/TestBundle/WamEntity' : __DIR__ . '/../SupportFiles/';
		
		$entity = new Entity('TestEntity');
		$entity->setDestinationFile($dir)
			->setExtends('AbstractEntity')
			->setImplements('AssetDefinition')
			->addUses('Wam\AssetBundle\Entity\Base\AbstractEntity')
			->addUses('Wam\AssetBundle\Entity\Base\AssetDefinition')
			->setPermission('final')
			->addProperty(new Property('protected', 'dirs', array('1', '2', '3')));
		if($namespace) {
			$entity->setNamespace($namespace);
		}

		return $entity;
	}
	
	
	
	
	

}