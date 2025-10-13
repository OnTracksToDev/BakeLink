<?php

namespace App\DataFixtures;

use App\Entity\PastryChef;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class PastryChefFixtures extends Fixture implements FixtureGroupInterface
{
    private $userPasswordHasherInterface;

    public static function getGroups(): array
    {
        return ['1'];
    }


    public function __construct(UserPasswordHasherInterface $userPasswordHasherInterface)
    {
        $this->userPasswordHasherInterface = $userPasswordHasherInterface;
    }
    public function load(ObjectManager $manager): void
    {
        $cities = ["Paris", "Lyon", "Marseille"];
        for ($count = 6; $count < 11; $count++) {
            $pastryChef = new PastryChef();
            $pastryChef->setEmail("chef" . $count . "@mail.com");
            $pastryChef->setRoles(["ROLE_CHEF"]);
            $pastryChef->setPassword($this->userPasswordHasherInterface->hashPassword($pastryChef, "password"));
            $pastryChef->setFirstname("ChefFirstName" . $count);
            $pastryChef->setLastname("ChefLastName" . $count);
            $pastryChef->setPseudo("ChefPseudo" . $count);
            $pastryChef->setPhone("0102030405");
            $pastryChef->setCity($cities[array_rand($cities)]);
            $pastryChef->setSocialLink("https://www.facebook.com/chef" . $count);
            $pastryChef->setPhotoUrl("https://plus.unsplash.com/premium_photo-1664392388804-d728a3637d7f?q=80&w=1941&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D");
            $pastryChef->setIsVerified(true);
            $pastryChef->setIsProfileCompleted(true);
            $pastryChef->setExperience("Expérience chef . $count");
            $pastryChef->setPrice("50.00,10.00");
            $pastryChef->setSpeciality("Bakery");
            $pastryChef->setWebsiteLink("https://www.chef" . $count . "website.com");
            $manager->persist($pastryChef);
        }

        $manager->flush();
    }
}
