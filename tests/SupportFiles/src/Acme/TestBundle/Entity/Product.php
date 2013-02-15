<?php
namespace Acme\TestBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Wam\AssetBundle\Annotations as WAM;

/**
 * @ORM\Entity
 * @WAM\Entity
 */
class Product
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
	 * Price
	 * @ORM\Column(type="decimal", scale=2)
	 * @var flaot
	 **/
	protected $price;

	/**
	 * Description
	 * @ORM\Column(type="string")
	 * @var string
	 **/
	protected $description;

	/**
	 * Directory Structure
	 * @WAM\Dirs
	 * @var array
	 **/
	protected $dirs = array(
		'products/{id}/images',
		'products/{id}/images/100',
		'products/{id}/images/200',
		'products/{id}/images/800',
		'products/{id}/documents'
	);


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
	 * Get Price
	 * @return float
	 **/
	public function getPrice()
	{
		return $this->price;
	}

	/**
	 * Set Price
	 * @param float $price
	 * @return Acme\TestBundle\Entity\Product
	 **/
	public function setPrice($price)
	{
		$this->price = $price;
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

	/**
	 * Get Dirs
	 * @return array
	 **/
	public function getDirs()
	{
		return $this->dirs;
	}
	

}