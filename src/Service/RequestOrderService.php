<?php

namespace App\Service;

use App\Entity\RequestOrder;
use App\Entity\PastryChef;
use App\Entity\Client;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;

class RequestOrderService
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function createFromPastryChef(PastryChef $pastryChef, Security $security): RequestOrder
    {
        $user = $security->getUser();
        $requestOrder = new RequestOrder();
        if ($user instanceof Client) {
            $requestOrder->setClient($user);
            $requestOrder->setPastryChef($pastryChef);
        }
        return $requestOrder;
    }
}
