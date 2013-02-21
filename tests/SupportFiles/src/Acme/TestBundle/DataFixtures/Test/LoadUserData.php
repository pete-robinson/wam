<?php
namespace Acme\TestBundle\DataFixtures\Test;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Acme\TestBundle\Entity\User;

class LoadUserData extends AbstractFixture implements OrderedFixtureInterface
{

    public function getOrder() {
		return 1;
    }

    public function load(ObjectManager $manager) {
        $user = new User();

        $user->setName('A Test User');

        $manager->persist($user);
        $manager->flush();
    }

}