<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ShopProductController extends AbstractController
{
    /**
     * @Route("/shop/product", name="app_shop_product")
     */
    public function index(): Response
    {
        return $this->render('shop_product/index.html.twig', [
            'controller_name' => 'ShopProductController',
        ]);
    }
}
