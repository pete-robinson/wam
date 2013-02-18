<?php
namespace Wam\AssetBundle\Container;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

abstract class AbstractContainerAware implements ContainerAwareInterface
{
	/**
     * Service Container Interface
     * @var Symfony\Component\DependencyInjection\ContainerInterface
     */
    protected $serviceContainer;

    /**
     * Set the service container
     * @param ContainerInterface $container 
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->serviceContainer = $container;
    }

    /**
     * Get the serice container
     * @return ContainerInterface
     */
    public function getContainer()
    {
        return $this->serviceContainer;
    }
}