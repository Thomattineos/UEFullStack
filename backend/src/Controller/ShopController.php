<?php

namespace App\Controller;

use App\Entity\Shop;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ShopController extends AbstractController
{
    /**
     * @Route("/shop", name="app_shop")
     */
    public function index(): Response
    {
        return $this->render('shop/index.html.twig', [
            'controller_name' => 'ShopController',
        ]);
    }

    /**
     * @Route("/api/shops", name="api_shops_list")
     */
    public function getShops(EntityManagerInterface $entityManager): JsonResponse
    {
        $shops = $entityManager->getRepository(Shop::class)->findAll();
        $data = [];

        foreach ($shops as $shop) {
            $data[] = [
                'id' => $shop->getId(),
                'name' => $shop->getName(),
                'openingHours' => $shop->getOpeningHours(),
                'closingHours' => $shop->getClosingHours(),
                'leave' => $shop->isLeave()
            ];
        }

        return new JsonResponse($data);
    }
}
