<?php
namespace Wam\AssetBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;


class WamAssetExtension extends Extension
{

	public function load(array $configs, ContainerBuilder $container)
	{
		$loader = new XmlFileLoader($container, new FileLocator(array(__DIR__ . '/../Resources/config')));
		$loader->load('services.xml');
	}


}