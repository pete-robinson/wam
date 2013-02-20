<?php
require_once __DIR__ . '/../WamTestCase.php';

use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Wam\AssetBundle\Asset\Directory\Directory;

class DirectoryTest extends WamTestCase
{
	private $directory;

	private $dirName = '1';

	private $path = 'assets/test/1';

	public function setUp()
	{
		parent::setUp();
		$this->dir = new Directory($this->dirName, $this->path, __DIR__ . '/../SupportFiles/web/');
	}

	/**
	 * test set name
	 *
	 * @return void
	 **/
	public function testSetName()
	{
		$name = 'newdir';

		$this->dir->setName($name);
		$this->assertEquals($this->dir->getName(), $name);
	}

	/**
	 * test create and delete
	 * @return void
	 **/
	public function testCreateAndDelete()
	{
		$this->dir->delete();

		$this->dir->create();
		$this->assertTrue($this->dir->exists());

		$this->dir->delete();
		$this->assertFalse($this->dir->exists());
	}

	/**
	 * test GetPath
	 *
	 * @return void
	 **/
	public function testGetPath()
	{
		$this->assertEquals($this->dir->getPath(), $this->path);
	}
	
	
	

}