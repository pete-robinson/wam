<?php
namespace Acme\TestBundle\DataFixtures\Test;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Acme\TestBundle\Entity\Category;

class LoadCategoryData extends AbstractFixture implements OrderedFixtureInterface
{

    public function getOrder() {
		return 1;
    }

    public function load(ObjectManager $manager) {
        $category = new Category();

        $category->setName('A Test category')
        	->setDescription('A description for a test category');

        $manager->persist($category);
        $manager->flush();
    }

}