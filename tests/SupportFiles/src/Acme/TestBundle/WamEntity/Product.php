<?php
/*
 * This file is part of the Wam Web Asset Manager Package
 * This file was auto-generated by Wam
 *
 * (c) Pete Robinson <work@pete-robinson.co.uk>
 *
 * For the full copyright and license information, please view the LICENSE file
 */
namespace Acme\TestBundle\WamEntity;
use Wam\AssetBundle\Entity\Base\AbstractEntity;

final class Product extends AbstractEntity
{
	/**
	 * dirs
	 * @var array
	 */
	protected $dirs = array(
		'products/{id}',
		'products/{id}/images',
		array(
			'path' => 'products/{id}/images/100',
			'method' => 'height',
			'height' => '100',
			'width' => '0'
		),
		array(
			'path' => 'products/{id}/images/200',
			'width' => '200',
			'height' => '150',
			'method' => 'width'
		),
		array(
			'path' => 'products/{id}/images/400',
			'method' => 'width',
			'width' => '400',
			'height' => '0'
		),
		array(
			'path' => 'products/{id}/images/800',
			'width' => '800',
			'method' => 'width'
		),
		array(
			'path' => 'products/{id}/images/1000',
			'method' => 'square',
			'width' => '1000',
			'height' => '1000'
		),
		'products/{id}/documents'
	);

	
}