<?php

namespace App\DataFixtures;

use App\Entity\Client;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;


class ClientFixtures extends Fixture implements FixtureGroupInterface
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

        for ($count = 1; $count < 6; $count++) {
            $client = new Client();
            $client->setEmail("client" . $count . "@mail.com");
            $client->setRoles(["ROLE_CLIENT"]);
            $client->setPassword($this->userPasswordHasherInterface->hashPassword($client, "password"));
            $client->setFirstname("ClientFirstName" . $count);
            $client->setLastname("ClientLastName" . $count);
            $client->setPseudo("ClientPseudo" . $count);
            $client->setPhone("0102030405");
            $client->setCity($cities[array_rand($cities)]);
            $client->setSocialLink("https://www.facebook.com/client" . $count);
            $client->setPhotoUrl("https://cdn.pixabay.com/photo/2014/04/03/10/32/user-310807_1280.png");
            $client->setIsVerified(true);
            $client->setIsProfileCompleted(true);
            $manager->persist($client);
        }

        $manager->flush();
    }
}
