<?php
/*
 * This file is part of the Wam Web Asset Manager Package
 *
 * (c) Pete Robinson <work@pete-robinson.co.uk>
 *
 * For the full copyright and license information, please view the LICENSE file
 */
 
require_once __DIR__ . '/../WamTestCase.php';

use Wam\AssetBundle\Entity\Entity;
use Wam\AssetBundle\Entity\Property\Property;

class PropertyTest extends WamTestCase
{
	public function setUp()
	{
		parent::setUp();
	}

	/**
	 * test add Property
	 * @return void
	 **/
	public function testAddProperty()
	{
		$property = new Property('public', 'testProperty', 'a string');

		$entity = new Entity('TestEntity');
		$entity->addProperty($property);

		$ps = $entity->getProperties();
		
		$this->assertInstanceof('Wam\AssetBundle\Entity\Property\Property', $ps[0]);
		$this->assertEquals($ps[0]->getName(), 'testProperty');
		$this->assertEquals($ps[0]->getVisibility(), 'public');
		$this->assertEquals($ps[0]->getValue(), 'a string');
		$this->assertEquals($ps[0]->getType(), 'string');
	}

	/**
	 * fail add property on visibility
	 * @return void
	 **/
	public function testAddPropertyFailVisibility()
	{
		$this->setExpectedException('\InvalidArgumentException');
		$property = new Property('test', 'atest', 'a string');

		$this->setExpectedException('\InvalidArgumentException');
		$property = new Property('public', 'a@test', 'a string');
	}

	/**
	 * fail add property on name
	 * @return void
	 **/
	public function testAddPropertyFailName()
	{
		$this->setExpectedException('\InvalidArgumentException');
		$property = new Property('public', 'a@test', 'a string');
	}

	/**
	 * Test render
	 * @return void
	 **/
	public function testRender()
	{
		$property = new Property('public', 'test', 'testing');

		$result = $property->render();
		
		$this->assertContains('public $test = \'testing\'', $result);
	}
	
}