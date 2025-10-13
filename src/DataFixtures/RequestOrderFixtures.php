<?php

namespace App\DataFixtures;

use DateTime;
use App\Entity\Client;
use DateTimeImmutable;
use App\Entity\PastryChef;
use App\Entity\RequestOrder;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;

class RequestOrderFixtures extends Fixture implements FixtureGroupInterface
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
                $requestOrder = new RequestOrder();
                $requestOrder->setDescription("Description de la commande pour le client {$client->getId()} et le pâtissier {$pastryChef->getId()}.");
                $requestOrder->setStatus('En attente');
                $requestOrder->setEventDate((new DateTimeImmutable())->modify('+10 days'));
                $requestOrder->setFinishedAt(null);
                $requestOrder->setClient($client);
                $requestOrder->setPastryChef($pastryChef);
                $manager->persist($requestOrder);
            }
        }
        $manager->flush();
    }
    public function getDependencies()
    {
        return array(
            ClientFixtures::class,
            PastryChefFixtures::class,
        );
    }
}
