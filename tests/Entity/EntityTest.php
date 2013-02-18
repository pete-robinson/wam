<?php
require_once __DIR__ . '/../WamTestCase.php';

use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Wam\AssetBundle\Command\BuildCommand;

class EntityTest extends WamTestCase
{

	public function setUp()
	{
		parent::setUp();
	}

	/**
	 * test
	 *
	 * @return void
	 **/
	public function testTest()
	{
		$this->container->get('wam.common');
		exit('here');
	}
	
	

}