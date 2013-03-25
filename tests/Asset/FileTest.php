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
		$this->file = new File('../../tmp/files/logo.jpg');
		$this->dir = new Directory('web/newdir');
		$this->dir->create();
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

		$this->assertTrue($this->file->exists());
	}

	public function testFileIsDeleted()
	{
		$this->file->setDestination($this->dir);
		
		$this->file->create();
		
		$this->assertTrue($this->file->exists());
		$this->file->delete();
		
		$this->assertFalse($this->file->exists());
	}

	public function testIsUpload()
	{
		$this->file->setIsUpload(true);
		$this->assertTrue($this->file->getIsUpload());
	}

	public function testPathResolvesFromAbsolute()
	{
		$file = new File('/Users/pete.robinson/Sites/wam/asset/tmp/files/logo.jpg', true);

		$this->assertEquals($file->getRootPath(), '/Users/pete.robinson/Sites/wam/asset/tests/SupportFiles/../../tmp/files/logo.jpg');
	}

	public function testPathResolvesFromRoot()
	{
		$file = new File('/private/var/tmp/phphNIt7H', true);
		$this->assertEquals($file->getRootPath(), '/Users/pete.robinson/Sites/wam/asset/tests/SupportFiles/../../../../../../../private/var/tmp/phphNIt7H');
	}

	public function testPathResolvesFromAbsoluteInSameDir()
	{
		$file = new File('/Users/pete.robinson/Sites/wam/asset/tests/SupportFiles/test.jpg', true);

		$this->assertEquals($file->getRootPath(), '/Users/pete.robinson/Sites/wam/asset/tests/SupportFiles/./test.jpg');
	}

}