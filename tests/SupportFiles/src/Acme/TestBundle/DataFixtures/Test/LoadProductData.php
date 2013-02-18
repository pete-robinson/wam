<?php
namespace Acme\TestBundle\DataFixtures\Test;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Acme\TestBundle\Entity\Product;

class LoadProductData extends AbstractFixture implements OrderedFixtureInterface
{

    public function getOrder() {
		return 1;
    }

    public function load(ObjectManager $manager) {
        $product = new Product();

        $product->setName('A Test Product')
        	->setPrice('9.99')
        	->setDescription('A description for a test product');

        $manager->persist($product);
        $manager->flush();
    }

}