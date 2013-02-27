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
use Wam\AssetBundle\Asset\Directory\Directory;
use Wam\AssetBundle\Asset\File\File;

class FileTest extends WamTestCase
{
	
	private $file;

	private $dir;

	public function setUp()
	{
		parent::setUp();
		$this->file = new File('logo.jpg', '', '/Users/pete.robinson/Sites/wam/asset/tmp/files');
		$this->dir = new Directory('1', 'assets/test/1', $this->appKernel->getRootDir() . '/../web/');
		$this->dir->clean();
	}

	public function testFileExists()
	{
		$this->assertTrue($this->file->exists());
	}

	public function testExceptionThrownWhenCreatingWithNoDestination()
	{
		$this->setExpectedException('Wam\AssetBundle\Exception\WamException');
		$this->file->create();
	}

	public function testFileIsCreated()
	{
		$this->file->setDestination($this->dir);

		$this->file->create();
		
		$this->assertFileExists($this->dir->getRealPath() . '/' . $this->file->getName());
	}

	public function testFileIsDeleted()
	{
		$this->file->setDestination($this->dir);
		$file = $this->file->create();

		$this->assertTrue($file->exists());
		$file->delete();
		
		$this->assertFalse($file->exists());
	}

	public function testToString()
	{
		$this->assertEquals((string) $this->file, 'logo.jpg');
	}

	public function testIsUpload()
	{
		$this->file->setIsUpload(true);
		$this->assertTrue($this->file->isUpload());
	}

}