<?php
namespace Acme\TestBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Category
{

	/**
	 * Id
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 * @var integer
	 **/
	protected $id;

	/**
	 * Name
	 * @ORM\Column(type="string")
	 * @var string
	 **/
	protected $name;

	/**
	 * Description
	 * @ORM\Column(type="string")
	 * @var string
	 **/
	protected $description;


	/**
	 * Get Id
	 * @return integer
	 **/
	public function getId()
	{
		return $this->id;
	}

	/**
	 * Get Name
	 * @return string
	 **/
	public function getName()
	{
		return $this->name;
	}

	/**
	 * Set Name
	 * @param string $name
	 * @return Acme\TestBundle\Entity\Product
	 **/
	public function setName($name)
	{
		$this->name = $name;
		return $this;
	}

	/**
	 * Get Description
	 * @return string
	 **/
	public function getDescription()
	{
		return $this->description;
	}

	/**
	 * Set Description
	 * @param string $description
	 * @return Acme\TestBundle\Entity\Product
	 **/
	public function setDescription($description)
	{
		$this->description = $description;
		return $this;
	}
	

}