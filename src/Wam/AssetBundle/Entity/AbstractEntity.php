<?php
namespace Wam\AssetBundle\Entity;
use Wam\AssetBundle\Container\AbstractContainerAware;

class AbstractEntity extends AbstractContainerAware
{

	public function __construct()
	{
		echo 'here';
	}

}