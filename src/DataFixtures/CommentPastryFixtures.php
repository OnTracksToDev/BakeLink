<?php

namespace App\DataFixtures;

use App\Entity\Client;
use App\Entity\Pastry;
use App\Entity\CommentPastry;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;

class CommentPastryFixtures extends Fixture implements FixtureGroupInterface
{
    public static function getGroups(): array
    {
        return ['2'];
    }
    public function load(ObjectManager $manager): void
    {
        $clients = $manager->getRepository(Client::class)->findAll();
        $pastries = $manager->getRepository(Pastry::class)->findAll();
        foreach ($clients as $client) {
            foreach ($pastries as $pastry) {
                for ($i = 1; $i < 4; $i++) {
                    $comment = new CommentPastry();
                    $comment->setContentComment("Commentaire #$i pour la pâtisserie Id: " . $pastry->getId());
                    $comment->setClient($client);
                    $comment->setPastry($pastry);
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
            PastryFixtures::class,
        ];
    }
}
