<?php

namespace App\Controller;

use App\Entity\Shop;
use App\Repository\ShopRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ShopController extends AbstractController
{
    /**
     * @Route("/api/shop", name="app_shop")
     */
    public function index(): Response
    {
        return $this->render('shop/index.html.twig', [
            'controller_name' => 'ShopController',
        ]);
    }

    /**

    @Route("/api/shops", name="get_shops", methods={"GET"})
     */
    public function getShops(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $page = $request->query->getInt('page', 1);
        $pageSize = 8;
        $sort = $request->query->get('sortBy');
        $sortOrder = $request->query->get('sortOrder', 'asc');

        $criteria = [];
        $orderBy = [];

        if ($sort === 'name') {
            $orderBy['name'] = $sortOrder === 'asc' ? 'ASC' : 'DESC';
        } elseif ($sort === 'creationDate') {
            $orderBy['creationDate'] = $sortOrder === 'asc' ? 'ASC' : 'DESC';
        }

        $totalShops = $entityManager->getRepository(Shop::class)->count($criteria);
        $totalPages = ceil($totalShops / $pageSize);
        $offset = ($page - 1) * $pageSize;

        $shops = $entityManager->getRepository(Shop::class)->findBy($criteria, $orderBy, $pageSize, $offset);

        $data = [];

        foreach ($shops as $shop) {
            $data[] = [
                'id' => $shop->getId(),
                'name' => $shop->getName(),
                'openingHours' => $shop->getOpeningHours(),
                'closingHours' => $shop->getClosingHours(),
                'available' => $shop->isAvailable(),
                'creationDate' => $shop->getCreationDate()
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
            'creationDate' => $shop->getCreationDate()
        ];

        return new JsonResponse($data, Response::HTTP_OK);
    }


    /**
     * @Route("/api/shops", name="create_shop", methods={"POST"})
     */
    public function createShop(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        $shop = new Shop();

        if(isset($data['name']) && ($data['name']) != "")
            $shop->setName($data['name']);
        else
            return new Response('le nom de boutique ne peut pas être vide', Response::HTTP_BAD_REQUEST);

        if(isset($data['openingHours']) && ($data['openingHours']) != "") {
            if(!preg_match('/^([0-1][0-9]|2[0-3]):([0-5][0-9])$/', $data['openingHours']))
                return new Response('Le format de openingHours doit être "hh:mm"', Response::HTTP_BAD_REQUEST);
            $openingHours = new DateTime($data['openingHours']);
            $shop->setOpeningHours($openingHours);
        }
        else return new Response('les horaires d\'ouverture ne peuvent pas être vides', Response::HTTP_BAD_REQUEST);

        if(isset($data['closingHours']) && ($data['closingHours']) != "") {
            if(!preg_match('/^([0-1][0-9]|2[0-3]):([0-5][0-9])$/', $data['closingHours']))
                return new Response('Le format de closingHours doit être "hh:mm"', Response::HTTP_BAD_REQUEST);
            $closingHours = new DateTime($data['closingHours']);
            $shop->setClosingHours($closingHours);
        }
        else return new Response('les horaires de fermeture ne peuvent pas être vides', Response::HTTP_BAD_REQUEST);

        $shop->setAvailable($data['available']);

        $now = new DateTime();

        $shop->setCreationDate($now);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($shop);
        $entityManager->flush();

        return new JsonResponse(['status' => 'Shop created!'], Response::HTTP_CREATED);
    }


    /**
     * @Route("/api/shops/{id}", name="update_shop", methods={"PUT"})
     * @throws \Exception
     */
    public function updateShop(Request $request, EntityManagerInterface $entityManager, int $id): Response
    {
        $shop = $entityManager->getRepository(Shop::class)->find($id);

        if (!$shop) {
            return new JsonResponse(['erreur' => 'Boutique non trouvée.'], Response::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);

        if(isset($data['name']) && ($data['name']) != "")
            $shop->setName($data['name']);
        else
            return new Response('le nom de boutique ne peut pas être vide', Response::HTTP_BAD_REQUEST);

        if(isset($data['openingHours']) && ($data['openingHours']) != "") {
            if(!preg_match('/^([0-1][0-9]|2[0-3]):([0-5][0-9])$/', $data['openingHours']))
                return new Response('Le format de openingHours doit être "hh:mm"', Response::HTTP_BAD_REQUEST);
            $openingHours = new DateTime($data['openingHours']);
            $shop->setOpeningHours($openingHours);
        }
        else return new Response('les horaires d\'ouverture ne peuvent pas être vides', Response::HTTP_BAD_REQUEST);

        if(isset($data['closingHours']) && ($data['closingHours']) != "") {
            if(!preg_match('/^([0-1][0-9]|2[0-3]):([0-5][0-9])$/', $data['closingHours']))
                return new Response('Le format de closingHours doit être "hh:mm"', Response::HTTP_BAD_REQUEST);
            $closingHours = new DateTime($data['closingHours']);
            $shop->setClosingHours($closingHours);
        }
        else return new Response('les horaires de fermeture ne peuvent pas être vides', Response::HTTP_BAD_REQUEST);

        $shop->setAvailable($data['available']);


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
