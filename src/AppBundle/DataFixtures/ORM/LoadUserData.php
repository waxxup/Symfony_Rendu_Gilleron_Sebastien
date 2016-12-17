<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Nelmio\Alice\Loader\NativeLoader;


class LoadUserData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {


        $loader = new NativeLoader();
        $userSet = $loader->loadFile(__DIR__ . '/../../Resources/fixtures/orm/user/user.yml');

        foreach ($userSet->getObjects() as $user) {
            $manager->persist($user);
        }




        $manager->flush();

    }
}