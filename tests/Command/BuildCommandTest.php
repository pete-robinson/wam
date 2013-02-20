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

		$dir = __DIR__ . '/../SupportFiles/src/Acme/TestBundle';
		`chmod -R 777 {$dir}`;

		$this->application->add(new BuildCommand());

		$this->command = $this->application->find('wam:build');

		$this->tester = new CommandTester($this->command);
	}

	/**
	 * Text Execute
	 * @return void
	 **/
	public function testExecute()
	{
		$dir = __DIR__ . '/../SupportFiles/src/Acme/TestBundle/WamEntity';
		if(is_dir($dir)) {
			`rm -R {$dir}`;
		}

		$this->tester->execute(array(
			'command' => $this->command->getName(),
			'entity' => $this->getEntityNamespace()
		));

		$this->assertContains('Entity Created Successfully', $this->tester->getDisplay());

		$this->assertfileExists($this->command->getWamEntityPath());

		$this->assertTrue(is_writable($this->command->getWamEntityPath()));
	}
	

	/**
	 * Test to check for exceptions being thrown if the class passed is not a WAM class
	 * @return void
	 **/
	public function testNonWamClass()
	{
		$this->setExpectedException('InvalidArgumentException');

		$this->tester->execute(array(
			'command' => $this->command->getName(),
			'entity' => 'Acme\TestBundle\Entity\Category'
		));
	}

	/**
	 * Test to check for exceptions being thrown if the class passed does not have any properties
	 * @return void
	 **/
	public function testNoProperties()
	{
		$this->setExpectedException('InvalidArgumentException');

		$this->tester->execute(array(
			'command' => $this->command->getName(),
			'entity' => 'Acme\TestBundle\Entity\User'
		));
	}

	/**
	 * Test to check for exceptions being thrown if the class passed does not have any Wam\Dir properties
	 * @return void
	 **/
	public function testNoWamProperties()
	{
		$this->setExpectedException('InvalidArgumentException');

		$this->tester->execute(array(
			'command' => $this->command->getName(),
			'entity' => 'Acme\TestBundle\Entity\Content'
		));
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