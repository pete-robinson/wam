<?php
/*
 * This file is part of the Wam Web Asset Manager Package
 *
 * (c) Pete Robinson <work@pete-robinson.co.uk>
 *
 * For the full copyright and license information, please view the LICENSE file
 */
 
namespace Wam\AssetBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Wam\AssetBundle\Common\Wam;
use Wam\AssetBundle\Exception\WamException;

class BuildCommand extends ContainerAwareCommand
{
	/**
	 * configure - sets command line function
	 * @return void
	 **/
	protected function configure()
	{
		$this->setName('wam:build')
			->setDescription('Build asset functionality for a single Entity')
			->addArgument('entity', InputArgument::REQUIRED, 'Which entity do you want to create asset functionality for? (Namespace)');

		$this->loadAnnotations();
	}

	/**
	 * Takes the target entity
	 *
	 * @return void
	 **/
	protected function execute(InputInterface $input, OutputInterface $output)
	{
		// gets the entity from the user provided arguments
		$entity = $input->getArgument('entity');

		try {
			$wam = $this->getContainer()->get('wam');
			$wam->create($entity);

			$output->writeln('Congratulations, the WamEntity was created successfully');
		} catch(WamException $e) {
			$output->writeln('ERROR: ' . $e->getMessage());
		}
	}

	/**
	 * Loads the annotations for Wam into the DoctrineCommon AnnotationRegistry
	 * @return void
	 **/
	protected function loadAnnotations()
	{
		AnnotationRegistry::registerFile(__DIR__.'/../Annotations/Dirs.php');
		AnnotationRegistry::registerFile(__DIR__.'/../Annotations/Entity.php');
	}

}