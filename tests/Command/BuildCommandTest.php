<?php
/*
 * This file is part of the Wam Web Asset Manager Package
 *
 * (c) Pete Robinson <work@pete-robinson.co.uk>
 *
 * For the full copyright and license information, please view the LICENSE file
 */
 
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
	 * Test Exception Thrown if passed an invalid path
	 * @return void
	 **/
	public function testExceptionThrownIfPassedAnInvalidPath()
	{
		$this->setExpectedException('Wam\AssetBundle\Exception\WamException');
		
		$wam = $this->container->get('wam');
		$wam->setWamEntityDir('test/testes/tests');

		$wam->create($this->getEntityNamespace());
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

		$this->assertContains('Congratulations, the WamEntity was created successfully', $this->tester->getDisplay());

		$this->assertfileExists(__DIR__ . '/../SupportFiles/src/Acme/TestBundle/WamEntity');

		$this->assertTrue(is_writable(__DIR__ . '/../SupportFiles/src/Acme/TestBundle/WamEntity'));
	}

	/**
	 * Text Execute on class without @Wam\Dir Property
	 * @return void
	 **/
	public function testExecuteOnClassWithoutWamDirProperty()
	{
		$this->tester->execute(array(
			'command' => $this->command->getName(),
			'entity' => 'Acme\TestBundle\Entity\User'
		));

		$this->assertContains('ERROR', $this->tester->getDisplay());
	}

	/**
	 * Text Execute on class that does not exist
	 * @return void
	 **/
	public function testExecuteOnClassThatDoesNotExist()
	{
		$this->tester->execute(array(
			'command' => $this->command->getName(),
			'entity' => 'Acme\TestBundle\Entity\NonExistantClass'
		));

		$this->assertContains('ERROR', $this->tester->getDisplay());
	}
	

	/**
	 * Test to check for exceptions being thrown if the class passed is not a WAM class
	 * @return void
	 **/
	public function testNonWamClass()
	{
		$this->tester->execute(array(
			'command' => $this->command->getName(),
			'entity' => 'Acme\TestBundle\Entity\Category'
		));

		$this->assertContains('ERROR', $this->tester->getDisplay());
	}

	/**
	 * Test to check for exceptions being thrown if the class passed does not have any properties
	 * @return void
	 **/
	public function testNoProperties()
	{
		$this->tester->execute(array(
			'command' => $this->command->getName(),
			'entity' => 'Acme\TestBundle\Entity\User'
		));

		$this->assertContains('ERROR', $this->tester->getDisplay());
	}

	/**
	 * Test to check for exceptions being thrown if the class passed does not have any Wam\Dir properties
	 * @return void
	 **/
	public function testNoWamProperties()
	{
		$this->tester->execute(array(
			'command' => $this->command->getName(),
			'entity' => 'Acme\TestBundle\Entity\Content'
		));

		$this->assertContains('ERROR', $this->tester->getDisplay());
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