<?php
namespace Wam\AssetBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Wam\AssetBundle\Annotations\Entity;
use Wam\AssetBundle\Annotations\Dirs;
use Wam\AssetBundle\Entity\EntityCreator;

class BuildCommand extends ContainerAwareCommand
{
	/**
	 * Entity to build
	 * @var Entity
	 **/
	private $entity;

	/**
	 * Dirs Property
	 * @var ReflectionProperty
	 **/
	private $dirsProperty = false;

	/**
	 * dirName
	 * Name of the directory to create in which generated entities are stored
	 * @var string
	 **/
	private $dirName = 'WamEntity';
	


	protected function configure()
	{
		$this
			->setName('wam:build')
			->setDescription('Build asset functionality for a single Entity')
			->addArgument('entity', InputArgument::REQUIRED, 'Which entity do you want to create asset functionality for? (Namespace)');

		$this->loadAnnotations();
	}

	protected function execute(InputInterface $input, OutputInterface $output)
	{
		// gets the entity from the user provided arguments
		$this->entity = $input->getArgument('entity');

		// check that entity is valid
		if($this->entityIsValid()) {

			// if it is, reflect the Entity and fetch it's properties
			$class_reflection = new \ReflectionClass($this->entity);
			$properties = $class_reflection->getProperties();
			
			// check that one of the properties is a Wam\Dir property
			if($properties) {
				foreach($properties as $property) {
					if($this->isDirProperty($property)) {
						$this->dirsProperty = $property;
					}
				}

				// assuming one exists, create the entity
				if($this->dirsProperty) {
					$this->createEntity();

					$output->writeln('Entity Created Successfully');
				} else {
					throw new \InvalidArgumentException('Could not locate @Wam\Dir in any class properties');
				}
			} else {
				throw new \InvalidArgumentException('Could not locate @Wam\Dir in any class property');
			}
		} else {
			throw new \InvalidArgumentException('Entity class must have @WAM\Entity prior to class definition');
		}
	}

	/**
	 * getEntity
	 * @return void
	 **/
	public function getEntity()
	{
		return $this->entity;
	}

	/**
	 * isValidEntity
	 * Checks that the entity being built is specificed as a WAM\Entity class
	 * @return boolean
	 **/
	protected function entityIsValid()
	{
		// load annotation reader
		$annotation_reader = new AnnotationReader();

		// reflect entity
		$reflection = new \ReflectionClass($this->entity);
		// pass entity annotations to annotation reader for formatting
		$annotations = $annotation_reader->getClassAnnotations($reflection);

		// loop through each annotation. if it contains @WAM\Entity, set flat to true
		$return = false;
		foreach($annotations as $annotation) {
			if($annotation instanceof Entity) {
				$return = true;
				break;
			}
		}

		return $return;
	}

	/**
	 * isDirProperty
	 * Checks whether property has @WAM\Dirs defined
	 * @param \ReflectionProperty $property
	 * @return boolean
	 **/
	protected function isDirProperty($property)
	{
		// init AnnotationReader
		$annotation_reader = new AnnotationReader();

		// refleft the property
		$property_reflection = new \ReflectionProperty($this->entity, $property->getName());
		// pass annotations to annotation reader to format
		$annotations = $annotation_reader->getPropertyAnnotations($property_reflection);

		// loop through annotations. If one is an instance of Dirs, set Flag to true
		$return = false;
		foreach($annotations as $annotation) {
			if($annotation instanceof Dirs) {
				$return = true;
				break;
			}
		}

		return $return;
	}

	/**
	 * createEntity
	 * @return void
	 **/
	private function createEntity()
	{
		$this->createWamAssetDirectory();
		$entity = new EntityCreator();
		$entity->setAssetPath($this->getWamEntityPath());
		$entity->create($this->getEntity());
	}

	/**
	 * createWamAssetDirectory
	 * if WamAsset directory for user specificed bundle does not exist, create it
	 * @return void
	 **/
	private function createWamAssetDirectory()
	{
		// create the directory if it does not exist
		$entity_path = $this->getWamEntityPath();
		
		if(!is_dir($entity_path)) {
			mkdir($entity_path, 0777);
			chmod($entity_path, 0777);
		}
		
		if(!is_dir($entity_path) || !is_writable($entity_path)) {
			throw new \Exception('Could not create entity path. Check your bundle permissions');
		}

	}

	/**
	 * getWamEntityDir
	 * returns the directory of the wam entities for the user speicifced bundle
	 * @return string
	 **/
	public function getWamEntityPath()
	{
		// get the kernel
		$kernel = $this->getApplication()->getKernel();
		// get the kernel rood dir
		$kernel_dir = $kernel->getRootDir();

		// get the list of installed bundles from the Kernel
		$bundles = $kernel->getBundles();

		// get the bundle and it's parent name from the user provided entify and combine them into one string
		$arr = explode('\\', $this->getEntity());
		$bundle_name = $arr[0] . $arr[1];

		// get the bundle path by using $bundle_name as a key in the $bundles array
		$bundle_path = $bundles[$bundle_name]->getPath();

		// append WamEntity to the path
		$entity_path = $bundle_path . '/' . $this->getDirName();

		return $entity_path;
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 **/
	public function getDirName()
	{
		return $this->dirName;
	}


	

	/**
	 * loadAnnotations
	 * Loads the annotations for Wam into the DoctrineCommon AnnotationRegistry
	 * @return void
	 **/
	protected function loadAnnotations()
	{
		AnnotationRegistry::registerFile(__DIR__.'/../Annotations/Dirs.php');
		AnnotationRegistry::registerFile(__DIR__.'/../Annotations/Entity.php');
	}

}