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
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Wam\AssetBundle\Asset\Directory\Directory;
use Wam\AssetBundle\Asset\File\File;

class DirectoryTest extends WamTestCase
{
	private $directory;

	private $dirName = '1';

	private $path = 'assets/test/1';

	private $testDirName = 'newdir';

	public function setUp()
	{
		parent::setUp();
		$this->dir = new Directory('web/' . $this->testDirName);
		$this->dir->create();
	}

	/**
	 * test set name
	 *
	 * @return void
	 **/
	public function testSetName()
	{
		$this->assertEquals($this->dir->getName(), $this->testDirName);
	}

	/**
	 * test create and delete
	 * @return void
	 **/
	public function testCreateAndDelete()
	{
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
		$this->assertEquals($this->dir->getWebPath(), '/web/' . $this->testDirName);
	}

	/**
	 * test cleanDir
	 * @return void
	 **/
	public function testCleanDir()
	{
		$file = __DIR__ . '/../../tmp/files/logo.jpg';

		copy($file, $this->dir->getRootPath() . '/' . basename($file));

		$this->assertTrue(file_exists($this->dir->getRootPath() . '/' . basename($file)));

		$this->dir->clean();

		$this->assertFalse(file_exists($this->dir->getRootPath() . '/' . basename($file)));

		$this->dir->delete();
	}

	/**
	 * test directory->put
	 * @return void
	 **/
	public function testPut()
	{
		$file = new File('../../tmp/files/logo.jpg');

		$file->setDestination($this->dir);
		$file->create();

		$this->assertFileExists($this->dir->getRootPath() . '/' . $file->getName());
	}

	public function testGetParentDir()
	{
		$this->assertEquals($this->dir->getParentDir(), realpath($this->container->get('kernel')->getRootDir() . '/../web'));
	}

	public function testNoParentDirForDirectoryThatDoesntExist()
	{
		$dir = new Directory('web/arandomtestdirss');
		$this->assertNull($dir->getParentDir());
	}

	public function testRecearsiveDirectoryDeletion()
	{
		$dir = new Directory('web/arandomtestdir');
		$dir2 = new Directory('web/arandomtestdir/test');

		$dir->create();
		$dir2->create();

		$dir->delete();

		$this->assertFileNotExists($dir->getRootPath());
	}

	/**
	 * teardown method
	 * @return void
	 **/
	protected function tearDown()
	{
		$this->dir->delete();
		$this->dir->create();
	}
	

	
	
	
	
	
	

}