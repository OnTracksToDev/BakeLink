<?php

namespace App\DataFixtures;

use App\Entity\Pastry;
use App\Entity\PastryChef;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;

class PastryFixtures extends Fixture implements FixtureGroupInterface
{
    public static function getGroups(): array
    {
        return ['1'];
    }

    public function load(ObjectManager $manager): void
    {
        $pastryChefs = $manager->getRepository(PastryChef::class)->findAll();

        foreach ($pastryChefs as $pastryChef) {
            for ($i = 1; $i <= 5; $i++) {
                $pastry = new Pastry();
                $pastry->setPhotoUrl("https://images.pexels.com/photos/140831/pexels-photo-140831.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1");
                $pastry->setTitle("pâtisserie $i du Chef " . $pastryChef->getId());
                $pastry->setDescription("Description de la pâtisserie $i pour le Chef " . $pastryChef->getId());
                $pastry->setPastryChef($pastryChef);

                $manager->persist($pastry);
            }
        }

        $manager->flush();
    }
    public function getDependencies()
    {
        return array(
            PastryChefFixtures::class,
        );
    }
}
