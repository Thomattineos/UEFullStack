<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\Shop;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{

    /**
     * @Route("/api/product", name="app_product")
     */
    public function index(): Response
    {
        return $this->render('product/index.html.twig', [
            'controller_name' => 'ProductController',
        ]);
    }

    /**
     * @Route("/api/products", name="get_products", methods={"GET"})
     */
    public function getProducts(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $page = $request->query->getInt('page', 1);
        $pageSize = 8;
        $sort = $request->query->get('sortBy');
        $sortOrder = $request->query->get('sortOrder', 'asc');
        $search = $request->query->get('search');

        $criteria = [];
        $orderBy = [];

        if ($sort === 'name') {
            $orderBy['name'] = $sortOrder === 'asc' ? 'ASC' : 'DESC';
        } elseif ($sort === 'price') {
            $orderBy['price'] = $sortOrder === 'asc' ? 'ASC' : 'DESC';
        }

        $totalProducts = $entityManager->getRepository(Product::class)->count($criteria);
        $totalPages = ceil($totalProducts / $pageSize);
        $offset = ($page - 1) * $pageSize;

        if ($search) {
            $queryBuilder = $entityManager->createQueryBuilder();
            $queryBuilder->select('p')
                ->from(Product::class, 'p')
                ->andWhere($queryBuilder->expr()->like('p.name', ':search'))
                ->setParameter('search', '%' . $search . '%');
            $products = $queryBuilder
                ->addOrderBy('p.name', 'ASC')
                ->addOrderBy('p.price', 'DESC')
                ->setFirstResult(($page - 1) * $pageSize)
                ->setMaxResults($pageSize)
                ->getQuery()
                ->getResult();
        } else {
            $products = $entityManager->getRepository(Product::class)->findBy($criteria, $orderBy, $pageSize, $offset);
        }

        $data = [];

        foreach ($products as $product) {
            $data[] = [
                'id' => $product->getId(),
                'name' => $product->getName(),
                'price' => $product->getPrice(),
                'description' => $product->getDescription()
            ];
        }

        $paginationData = [
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'totalProducts' => $totalProducts,
            'pageSize' => $pageSize,
            'nextPage' => $page < $totalPages ? $page + 1 : null,
            'prevPage' => $page > 1 ? $page - 1 : null,
        ];

        return new JsonResponse([
            'products' => $data,
            'pagination' => $paginationData,
        ]);
    }

    /**
     * @Route("/api/products/{id}", name="get_product_by_id", methods={"GET"})
     */
    public function getProductById(int $id): JsonResponse
    {
        $repository = $this->getDoctrine()->getRepository(Product::class);
        $product = $repository->find($id);

        if (!$product) {
            return new JsonResponse(['erreur' => 'Le produit n\'a pas été trouvé'], Response::HTTP_NOT_FOUND);
        }

        return $this->json($product);
    }


    /**
     * @Route("/api/products", name="create_product", methods={"POST"})
     */
    public function createProduct(Request $request): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $data = json_decode($request->getContent(), true);
        $product = new Product();

        if(isset($data['name']) && ($data['name']) != "")
            $product->setName($data['name']);
        else
            return new Response('le nom d\'un produit ne peut pas être vide', Response::HTTP_BAD_REQUEST);

        if(isset($data['price']) && ($data['price']) != "") {
            if(!preg_match('/^[+-]?([0-9]*[.])?[0-9]+$/', $data['price']))
                return new Response('le prix doit être un nombre entier ou décimal', Response::HTTP_BAD_REQUEST);

            $product->setPrice($data['price']);
        }
        else
            return new Response('le prix d\'un produit ne peut pas être vide', Response::HTTP_BAD_REQUEST);

        $product->setDescription($data['description']);

        $entityManager->persist($product);
        $entityManager->flush();

        return new JsonResponse(['message' => 'Le produit a été créé avec succès'], Response::HTTP_CREATED);
    }


    /**
     * @Route("/api/products/{id}", name="update_product", methods={"PUT"})
     */
    public function updateProduct(Request $request, $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $product = $entityManager->getRepository(Product::class)->find($id);

        if (!$product) {
            throw $this->createNotFoundException('Impossible de trouver le produit pour l\'id : '.$id);
        }

        $data = json_decode($request->getContent(), true);

        if(isset($data['name']) && ($data['name']) != "")
            $product->setName($data['name']);
        else
            return new Response('le nom d\'un produit ne peut pas être vide', Response::HTTP_BAD_REQUEST);

        if(isset($data['price']) && ($data['price']) != "") {
            if(!preg_match('/^[+-]?([0-9]*[.])?[0-9]+$/', $data['price']))
                return new Response('le prix doit être un nombre entier ou décimal', Response::HTTP_BAD_REQUEST);
            $product->setPrice($data['price']);
        }
        else
            return new Response('le prix d\'un produit ne peut pas être vide', Response::HTTP_BAD_REQUEST);

        if(isset($data['description']) && ($data['description']) != "")
            $product->setDescription($data['description']);
        else
            return new Response('la description ne peut pas être vide', Response::HTTP_BAD_REQUEST);

        $entityManager->flush();

        return new JsonResponse(['message' => 'Le produit a été modifié avec succès'], Response::HTTP_OK);
    }


    /**
     * @Route("/api/products/{id}", name="delete_product", methods={"DELETE"})
     */
    public function deleteProduct($id): JsonResponse
    {
        $entityManager = $this->getDoctrine()->getManager();
        $product = $entityManager->getRepository(Product::class)->find($id);

        if (!$product) {
            throw $this->createNotFoundException('Impossible de trouver le produit pour l\'id : '.$id);
        }

        $entityManager->remove($product);
        $entityManager->flush();

        return new JsonResponse(['message' => 'Le produit a été supprimé avec succès'], Response::HTTP_OK);
    }
}
