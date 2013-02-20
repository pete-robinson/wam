<?php
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

		$string = "
	/**
	 * $name
	 * @var $type
	 */
	";

		if(is_array($this->getValue())) {
			$string .= $this->getVisibility() . ' $' . $name . ' = array(' . "\n";
			$values = $this->getValue();

			foreach($values as $k => $item) {
				$string .= "		'" . $item . "'";
				if(isset($values[$k+1])) {
					$string .=  ",\n";
				} else {
					$string .= "\n";
				}
			}
			$string .= '	);';
		} else {
			$string .= $this->getVisibility() . ' $' . $name . " = '" . $this->getValue() . "';";
		}

		$string .= "\n";

		return $string;
	}
	

}