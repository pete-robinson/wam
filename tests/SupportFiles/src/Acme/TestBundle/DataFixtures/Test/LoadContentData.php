<?php
namespace Acme\TestBundle\DataFixtures\Test;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Acme\TestBundle\Entity\Content;

class LoadContentData extends AbstractFixture implements OrderedFixtureInterface
{

    public function getOrder() {
		return 1;
    }

    public function load(ObjectManager $manager) {
        $content = new Content();

        $content->setName('A Test Content')
        	->setDescription('a test desc');

        $manager->persist($content);
        $manager->flush();
    }

}