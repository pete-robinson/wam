<?php
/*
 * This file is part of the Wam Web Asset Manager Package
 *
 * (c) Pete Robinson <work@pete-robinson.co.uk>
 *
 * For the full copyright and license information, please view the LICENSE file
 */
 
namespace Wam\AssetBundle\Entity\Property;

class Property
{

	/**
	 * visibility
	 * @var string
	 **/
	private $visibility;

	/**
	 * Name
	 * @var string
	 **/
	private $name;

	/**
	 * Value
	 * @var mixed
	 **/
	private $value;

	/**
	 * Type
	 * @var string
	 **/
	private $type;

	/**
	 * Constructor
	 *
	 * @return void
	 **/
	public function __construct($visibility, $name, $value=false)
	{
		$this->setVisibility($visibility);
		$this->setName($name);
		$this->setValue($value);
		$this->setType(gettype($value));
	}

	/**
	 * getVisibility
	 * @return string
	 **/
	public function getVisibility()
	{
		return $this->visibility;
	}

	/**
	 * setVisibility
	 * @param string $visibility
	 * @return void
	 **/
	protected function setVisibility($visibility)
	{
		if(in_array($visibility, array('public', 'private', 'protected'))) {
			$this->visibility = $visibility;
		} else {
			throw new \InvalidArgumentException('Invalid property visibility specified');
		}
	}

	/**
	 * getName
	 * @return string
	 **/
	public function getName()
	{
		return $this->name;
	}

	/**
	 * setName
	 * @param string $name
	 * @return void
	 **/
	protected function setName($name)
	{
		if(preg_match('/^[a-z0-9_]+$/i', $name)) {
			$this->name = $name;
		} else {
			throw new \InvalidArgumentException('Invalid value supplied for "name".');
		}
	}

	/**
	 * getValue
	 * @return mixed
	 **/
	public function getValue()
	{
		return $this->value;
	}

	/**
	 * setValue
	 * @param mixed $value
	 * @return void
	 **/
	protected function setValue($value)
	{
		$this->value = $value;
	}

	/**
	 * getType
	 * @return mixed
	 **/
	public function getType()
	{
		return $this->type;
	}

	/**
	 * setType
	 * @param string $type
	 * @return void
	 **/
	protected function setType($type)
	{
		$this->type = $type;
	}

	/**
	 * Render
	 * @return string
	 **/
	public function render()
	{
		$name = $this->getName();
		$type = $this->getType();

		$string = "/**\n\t * $name\n\t * @var $type\n\t */\n\t";

		if(is_array($this->getValue())) {
			$string .= $this->getVisibility() . ' $' . $name . ' = array(' . "\n";
			$values = $this->getValue();

			foreach($values as $k => $item) {
				if(is_array($item)) {
					$string .= $this->itemToString($item);
				} else {
					if(is_numeric(basename($item))) {
						$string .= $this->itemToString($item) . "";
					} else {
						$string .= "\t\t'" . $item . "'";
					}
				}

				$string .= (isset($values[$k+1])) ? ",\n" : "\n";

			}
			$string .= "\t);";
		} else {
			$string .= $this->getVisibility() . ' $' . $name . " = '" . $this->getValue() . "';";
		}

		$string .= "\n";

		return $string;
	}

	/**
	 * converts a property into a string of values
	 * @param mixed $item
	 * @return string
	 **/
	private function itemToString($item)
	{
		$item = $this->formatItem($item);

		$string = "\t\tarray(\n";
		end($item);
		$end = key($item);
		reset($item);

		foreach($item as $node => $value) {
			$string .= "\t\t\t'$node' => '$value'";
			$string .= ($node == $end) ? "\n" : ",\n";
		}

		$string .= "\t\t)";

		return $string;
	}

	/**
	 * format item
	 * @param mixed $item
	 * @return array
	 **/
	private function formatItem($item)
	{
		if(is_array($item)) {
			$method = (array_key_exists('method', $item)) ? $item['method'] : 'width';
		
			$item['method'] = $method;

			switch($method) {
				case 'width':
					if(!array_key_exists('width', $item)) {
						$item['width'] = basename($item['path']);
					}

					$item['height'] = (array_key_exists('height', $item)) ? $item['height'] : 0;
					break;
				case 'height':
					if(!array_key_exists('height', $item)) {
						$item['height'] = basename($item['path']);
					}
					$item['width'] = (array_key_exists('width', $item)) ? $item['width'] : 0;
					break;
				case 'square':
					$item['width'] = basename($item['path']);
					$item['height'] = basename($item['path']);
					break;
			}
		} else {
			$arr = array(
				'path' => $item,
				'width' => basename($item),
				'height' => '0',
				'method' => 'width'
			);
			$item = $arr;
		}

		return $item;
	}
	
	

}