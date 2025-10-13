<?php

namespace App\DataFixtures;

use App\Entity\Client;
use App\Entity\CommentPastryChef;
use App\Entity\PastryChef;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;

class CommentPastryChefFixtures extends Fixture implements FixtureGroupInterface
{
    public static function getGroups(): array
    {
        return ['2'];
    }
    public function load(ObjectManager $manager): void
    {
        $clients = $manager->getRepository(Client::class)->findAll();
        $pastryChefs = $manager->getRepository(PastryChef::class)->findAll();
        foreach ($clients as $client) {
            foreach ($pastryChefs as $pastryChef) {
                for ($i = 1; $i < 4; $i++) {
                    $comment = new CommentPastryChef();
                    $comment->setContentComment("Commentaire #$i pour le pâtissier " . $pastryChef->getId());
                    $comment->setClient($client);
                    $comment->setPastryChef($pastryChef);
                    $manager->persist($comment);
                }
            }
        }
        $manager->flush();
    }
    public function getDependencies()
    {
        return [
            ClientFixtures::class,
            PastryChefFixtures::class,
        ];
    }
}
