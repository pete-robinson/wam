<?php
namespace Wam\AssetBundle\Entity;

class FileWriter
{

	/**
	 * name
	 * @var string
	 **/
	private $name;

	/**
	 * destination
	 * @var string
	 **/
	private $destination;

	/**
	 * Properties
	 * @var array
	 **/
	private $properties = array();

	/**
	 * Content of file
	 * @var string
	 **/
	private $content;


	/**
	 * Constructor
	 * @param string $name
	 * @return FileWriter
	 **/
	public function __construct($name = '')
	{
		$this->setName($name);
		return $this;
	}

	/**
	 * setName
	 * @param string $name
	 * @return FileWriter
	 **/
	public function setName($name)
	{
		$this->name = $name;
		return $this;
	}

	/**
	 * setDestination
	 * @param string $destination
	 * @return FileWriter
	 **/
	public function setDestination($destination)
	{
		$this->destination = $destination;
		return $this;
	}

	/**
	 * addProperty
	 * @param string $key
	 * @param mixed $value
	 * @return FileWriter
	 **/
	public function addProperty($key, $value)
	{
		$this->properties[$key] = $value;
		return $this;
	}

	/**
	 * setContent
	 * @param string $content
	 * @param mixed $value
	 * @return FileWriter
	 **/
	public function setContent($content)
	{
		$this->content = $content;
		return $this;
	}

	/**
	 * create
	 * @return void
	 **/
	public function create()
	{
		
	}
	

	

}