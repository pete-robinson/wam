<?php
/*
 * This file is part of the Wam Web Asset Manager Package
 *
 * (c) Pete Robinson <work@pete-robinson.co.uk>
 *
 * For the full copyright and license information, please view the LICENSE file
 */
 
namespace Wam\AssetBundle\Entity;
use Wam\AssetBundle\Entity\Property\Property;

class Entity
{

	/**
	 * Namespace
	 * @var string
	 **/
	private $namespace = false;

	/**
	 * Uses
	 * @var array
	 **/
	private $uses = array();

	/**
	 * Name
	 * @var string
	 **/
	private $name;

	/**
	 * Extends
	 * @var string
	 **/
	private $extends;

	/**
	 * Implements
	 * @var string
	 **/
	private $implements;

	/**
	 * Properties
	 * @var array
	 **/
	private $properties = array();

	/**
	 * class String
	 * @var string
	 **/
	private $classString;

	/**
	 * destination file
	 * @var string
	 **/
	private $destinationFile;

	/**
	 * permission
	 * @var string
	 **/
	private $permission;
	

	/**
	 * Constructor
	 * @param string $name
	 * @return Entity
	 **/
	public function __construct($name='')
	{
		$this->setName($name);
		return $this;
	}

	/**
	 * get namespace
	 * @return string
	 **/
	public function getNamespace($full=false)
	{
		return ($full) ? $this->namespace . '\\' . $this->getName() : $this->namespace;
	}

	/**
	 * set namespace
	 * @param string $namespace
	 * @return Entity
	 **/
	public function setNamespace($namespace)
	{
		$this->namespace = $namespace;
		return $this;
	}

	/**
	 * get uses
	 * @return array
	 **/
	public function getUses()
	{
		return $this->uses;
	}

	/**
	 * add uses
	 * @param string $use
	 * @return Entity
	 **/
	public function addUses($use)
	{
		$this->uses[] = $use;
		return $this;
	}

	/**
	 * get name
	 * @return string
	 **/
	public function getName()
	{
		return $this->name;
	}

	/**
	 * set name
	 * @param string $name
	 * @return Entity
	 **/
	public function setName($name)
	{
		$this->name = $name;
		return $this;
	}

	/**
	 * get extends
	 * @return string
	 **/
	public function getExtends()
	{
		return $this->extends;
	}

	/**
	 * set extends
	 * @param string $extends
	 * @return Entity
	 **/
	public function setExtends($extends)
	{
		$this->extends = $extends;
		return $this;
	}

	/**
	 * get implements
	 * @return string
	 **/
	public function getImplements()
	{
		return $this->implements;
	}

	/**
	 * set implements
	 * @param string $implements
	 * @return Entity
	 **/
	public function setImplements($implements)
	{
		$this->implements = $implements;
		return $this;
	}

	/**
	 * get properties
	 * @return array
	 **/
	public function getProperties()
	{
		return $this->properties;
	}

	/**
	 * add property
	 * @param string $property
	 * @return Entity
	 **/
	public function addProperty(Property $property)
	{
		$this->properties[] = $property;
		return $this;
	}

	/**
	 * get destination file
	 * @return string
	 **/
	public function getDestinationFile()
	{
		return $this->destinationFile;
	}

	/**
	 * set destination file
	 * @param string $destinationFile
	 * @return Entity
	 **/
	public function setDestinationFile($destinationFile)
	{
		$this->destinationFile = $destinationFile;
		return $this;
	}

	/**
	 * get permission
	 * @return string
	 **/
	public function getPermission()
	{
		return $this->permission;
	}

	/**
	 * set permission
	 * @param string $permission
	 * @return Entity
	 **/
	public function setPermission($permission)
	{
		$this->permission = $permission;
		return $this;
	}

	/**
	 * returns a string of the compiled class
	 * @return string
	 **/
	public function getClassString()
	{
		return $this->classString;
	}

	/**
	 * Auto generate namespace
	 * @return string
	 **/
	public function autoGenerateNamespace()
	{
		$arr = explode('src/', $this->getDestinationFile());

		if(count($arr) > 1) {
			$return = str_replace('/', '\\', $arr[1]);
		} else {
			$return = false;
		}

		return $return;
	}

	/**
	 * compile
	 * @return void
	 **/
	public function compile()
	{
		$template = $this->loadTemplate();

		if(!$this->getNamespace()) {
			$this->setNamespace($this->autoGenerateNamespace());
		}

		// add namespace to template
		$template = str_replace('{{namespace}}', 'namespace ' . $this->getNamespace() . ';', $template);

		// add uses to template
		if($this->getUses()) {
			$uses = array();
			foreach($this->getUses() as $use) {
				$uses[] = 'use ' . $use . ';';
			}

			$template = str_replace('{{use}}', implode("\n", $uses), $template);
		} else {
			$template = str_replace('{{use}}', '', $template);
		}

		// add permission
		if($this->getPermission()) {
			$template = str_replace('{{permission}}', $this->getPermission() . ' ', $template);
		} else {
			$template = str_replace('{{permission}}', '', $template);
		}

		// add class name
		$template = str_replace('{{classname}}', $this->getName(), $template);

		// extends
		if($this->getExtends()) {
			$template = str_replace('{{extends}}', ' extends ' . $this->getExtends(), $template);
		} else {
			$template = str_replace('{{extends}}', '', $template);
		}

		// implements
		if($this->getImplements()) {
			$template = str_replace('{{implements}}', ' implements ' . $this->getImplements(), $template);
		} else {
			$template = str_replace('{{implements}}', '', $template);
		}

		if($this->getProperties()) {
			$properties = array();
			foreach($this->getProperties() as $property) {
				$properties[] = $property->render();
			}

			$template = str_replace('{{properties}}', implode('', $properties), $template);
		} else {
			$template = str_replace('{{properties}}', '', $template);
		}

		$this->classString = $template;
	}

	/**
	 * save the file
	 * @return boolean
	 **/
	public function save()
	{
		$handle = fopen($this->getDestinationFile() . '/' . $this->getName() . '.php', 'w');
		fwrite($handle, $this->getClassString());
		fclose($handle);
	}

	/**
	 * load Template
	 * @return string
	 **/
	private function loadTemplate()
	{
		return file_get_contents(__DIR__ . '/../Resources/Templates/WamEntity.txt');
	}
	
	
	

}