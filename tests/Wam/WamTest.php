<?php
require_once __DIR__ . '/../WamTestCase.php';

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Wam\AssetBundle\Entity\Product;

class WamTest extends WamTestCase
{

	public function testTest()
	{
		$this->assertTrue(true);

		// AnnotationRegistry::registerFile(__DIR__.'/../../src/Wam/AssetBundle/Annotations/WamAnnotation.php');

		// $annotationReader = new AnnotationReader();
		// $reflection = new ReflectionProperty('Wam\AssetBundle\Entity\Product', 'dirs');

		// $classAnnotations = $annotationReader->getPropertyAnnotations($reflection);

		// echo 'PROPERTY ANNOTATIONS' . PHP_EOL;

		// var_dump($classAnnotations);

		
	}

}