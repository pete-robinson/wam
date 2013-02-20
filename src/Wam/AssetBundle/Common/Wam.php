<?php
namespace Wam\AssetBundle\Common;

use Wam\AssetBundle\Container\AbstractContainerAware;
use Wam\AssetBundle\Exception\WamException;

class Wam extends AbstractContainerAware
{

	/**
	 * Entity Manager
	 *
	 * @var Doctrine\ORM\EntityManager
	 **/
	private $em;

	/**
	 * initialize
	 * @return void
	 **/
	public function initialize()
	{
		$this->em = $this->getContainer()->get('doctrine')->getManager();
	}

	/**
	 * getEntity
	 * @param string $path
	 * @param mixed $doctrine_entity
	 * @return void
	 **/
	public function getEntity($path, $doctrine_entity)
	{
		$root_dir = $this->getContainer()->get('kernel')->getRootDir() . '/web/';
		$class = $this->getEntityNamespace($path);
		
		$this->entity = new $class($this->em, $doctrine_entity, $root_dir);

		return $this->entity;
	}

	/**
	 * getEntityNamespace
	 * @param string $path
	 * @return void
	 **/
	private function getEntityNamespace($path)
	{
		$namespace_array = explode(':', $path);

		if(count($namespace_array) > 1) {
			$bundle = $this->getContainer()->get('kernel')->getBundle($namespace_array[0]);

			$bundle_array = explode('\\', get_class($bundle));
			
			array_shift($namespace_array);

			$class = $bundle_array[0] . '\\' . $bundle_array[1];

			foreach($namespace_array as $namespace) {
				$class .= '\\' . $namespace;
			}

			return $class;
		} else {
			throw new WamException('Invalid WamEntity namespace provided');
		}
	}
	


}