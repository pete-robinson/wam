<?php
require_once __DIR__ . '/../WamTestCase.php';

use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Wam\AssetBundle\Command\BuildCommand;

class BuildCommandTest extends WamTestCase
{

	/**
	 * tester
	 * @var CommandTester
	 **/
	private $tester;

	/**
	 * Command
	 * @var BuildCommand
	 **/
	private $command;

	public function setUp()
	{
		parent::setUp();

		$this->application->add(new BuildCommand());

		$this->command = $this->application->find('wam:build');

		$this->tester = new CommandTester($this->command);
		$this->tester->execute(array(
			'command' => $this->command->getName(),
			'entity' => $this->getEntityNamespace()
		));
	}

	/**
	 * testEntity
	 * Test that the entity stored in the build command is the same as is passed in thsi file
	 * @return void
	 **/
	public function testEntity()
	{
		$this->assertEquals($this->getEntityNamespace(), $this->command->getEntity());
	}
	

	/**
	 * getEntityNamespace
	 * returns the entity namespace for testing
	 * @return Product
	 **/
	private function getEntityNamespace()
	{
		return 'Acme\TestBundle\Entity\Product';
	}
	

}