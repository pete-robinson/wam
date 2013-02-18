<?php
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Console\Input\ArrayInput;

class WamTestCase extends WebTestCase
{
	/**
	 * App Kernel
	 * @var AppKernel
	 **/
	protected $appKernel;

	/**
	 * Application
	 * @var Application
	 **/
	protected $application;

	/**
	 * Entity Manager
	 * @var Doctrine\ORM\EntityManager
	 **/
	protected $em;

	/**
	 * Conctainer
	 * @var DIC
	 **/
	protected $container;


	/**
	 * setUp
	 *
	 * @return void
	 **/
	public function setUp()
	{
		parent::setUp();

		$this->appKernel = $this->createKernel();
		$this->appKernel->boot();

		$this->application = new Application($this->appKernel);
		$this->application->setAutoExit(false);
		$this->container = $this->appKernel->getContainer();

		$this->em = $this->container->get('doctrine')->getManager();

		// $this->buildDb();

		// $this->runConsole('container:debug');
	}

	/**
	 * buildDb
	 * Builds the DB from the Entities in Acme\TestBundle\Entity
	 * @return void
	 **/
	private function buildDb()
	{
		$this->runConsole('doctrine:schema:drop', array('--force' => true));
		$this->runConsole('doctrine:schema:create');
		$this->runConsole('doctrine:fixtures:load', array('--fixtures' => 'tests/SupportFiles/bundles/Acme/TestBundle/DataFixtures/Test'));

	}

	/**
	 * runConsole
	 * Executes a console command
	 * @param string $command
	 * @param array $options
	 * @return mixed
	 **/
	private function runConsole($command, $options = array())
	{
		$options = array_merge($options, array('command' => $command));

		return $this->application->run(new ArrayInput($options));
	}
	

}