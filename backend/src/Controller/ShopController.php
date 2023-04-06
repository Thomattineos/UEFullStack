<?php

namespace App\Controller;

use App\Entity\Shop;
use App\Repository\ShopRepository;
use DateTime;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
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
     * @Route("/api/shops", name="api_shops_list", methods={"GET"})
     */
    public function getShops(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $page = $request->query->getInt('page', 1);
        $pageSize = 8;
        $totalShops = $entityManager->getRepository(Shop::class)->count([]);
        $totalPages = ceil($totalShops / $pageSize);
        $offset = ($page - 1) * $pageSize;
        $shops = $entityManager->getRepository(Shop::class)->findBy([], [], $pageSize, $offset);
        $data = [];

        foreach ($shops as $shop) {
            $data[] = [
                'id' => $shop->getId(),
                'name' => $shop->getName(),
                'openingHours' => $shop->getOpeningHours(),
                'closingHours' => $shop->getClosingHours(),
                'available' => $shop->isAvailable()
            ];
        }

        $paginationData = [
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'totalShops' => $totalShops,
            'pageSize' => $pageSize,
            'nextPage' => $page < $totalPages ? $page + 1 : null,
            'prevPage' => $page > 1 ? $page - 1 : null,
        ];

        return new JsonResponse([
            'shops' => $data,
            'pagination' => $paginationData,
        ]);
    }


    /**
     * @Route("/api/shops/{id}", name="get_shop_by_id", methods={"GET"})
     */
    public function getShopById(int $id, ShopRepository $shopRepository): JsonResponse
    {
        $shop = $shopRepository->find($id);

        if (!$shop) {
            return new JsonResponse(['message' => 'Boutique non trouvée'], Response::HTTP_NOT_FOUND);
        }

        $data = [
            'id' => $shop->getId(),
            'name' => $shop->getName(),
            'openingHours' => $shop->getOpeningHours(),
            'closingHours' => $shop->getClosingHours(),
            'available' => $shop->isAvailable(),
        ];

        return new JsonResponse($data, Response::HTTP_OK);
    }


    /**
     * @Route("/api/shops", name="add_shop", methods={"POST"})
     */
    public function addShop(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $shop = new Shop();
        $shop->setName($data['name']);

        $openingHours = new DateTime($data['openingHours']);
        $closingHours = new DateTime($data['closingHours']);

        //echo $openingHours;

        $shop->setOpeningHours($openingHours);
        $shop->setClosingHours($closingHours);
        $shop->setAvailable($data['available']);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($shop);
        $entityManager->flush();

        return new JsonResponse(['status' => 'Shop created!'], Response::HTTP_CREATED);
    }


    /**
     * @Route("/api/shops/{id}", name="update_shop", methods={"PUT"})
     * @throws \Exception
     */
    public function updateShop(Request $request, EntityManagerInterface $entityManager, int $id): JsonResponse
    {
        $shop = $entityManager->getRepository(Shop::class)->find($id);

        if (!$shop) {
            return new JsonResponse(['erreur' => 'Boutique non trouvée.'], Response::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);

        if (isset($data['name'])) {
            $shop->setName($data['name']);
        }

        if (isset($data['openingHours'])) {
            $openingHours = new \DateTime($data['openingHours']);
            $shop->setOpeningHours($openingHours);
        }

        if (isset($data['closingHours'])) {
            $closingHours = new \DateTime($data['closingHours']);
            $shop->setClosingHours($closingHours);
        }

        if (isset($data['available'])) {
            $shop->setAvailable($data['available']);
        }

        $entityManager->flush();

        return new JsonResponse(['message' => 'Boutique mise à jour.']);
    }


    /**
     * @Route("/api/shops/{id}", name="delete_shop", methods={"DELETE"})
     */
    public function deleteShop(EntityManagerInterface $entityManager, int $id): JsonResponse
    {
        $shop = $entityManager->getRepository(Shop::class)->find($id);

        if (!$shop) {
            throw $this->createNotFoundException('Boutique non trouvée');
        }

        $entityManager->remove($shop);
        $entityManager->flush();

        return new JsonResponse(['message' => 'Boutique supprimée avec succès']);
    }
}
