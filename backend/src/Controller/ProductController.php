<?php

namespace App\Controller;

use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    /**
     * @Route("/api/products", name="get_product", methods={"GET"})
     */
    public function getProducts(Request $request): JsonResponse
    {
        $page = $request->query->getInt('page', 1);
        $limit = $request->query->getInt('limit', 10);

        $repository = $this->getDoctrine()->getRepository(Product::class);
        $products = $repository->findBy([], null, $limit, ($page - 1) * $limit);

        $totalProducts = $repository->count([]);

        $data = [
            'products' => $products,
            'totalCount' => $totalProducts,
            'currentPage' => $page,
            'totalPages' => ceil($totalProducts / $limit),
        ];

        return $this->json($data);
    }

    /**
     * @Route("/api/products/{id}", name="get_product_by_id", methods={"GET"})
     */
    public function getProductById(int $id): JsonResponse
    {
        $repository = $this->getDoctrine()->getRepository(Product::class);
        $product = $repository->find($id);

        if (!$product) {
            return new JsonResponse(['error' => 'Product not found'], Response::HTTP_NOT_FOUND);
        }

        return $this->json($product);
    }


    /**
     * @Route("/api/products", name="create_product", methods={"POST"})
     */
    public function createProduct(Request $request): JsonResponse
    {
        $entityManager = $this->getDoctrine()->getManager();

        $data = json_decode($request->getContent(), true);

        $product = new Product();
        $product->setName($data['name']);
        $product->setPrice($data['price']);

        $entityManager->persist($product);
        $entityManager->flush();

        return new JsonResponse(['message' => 'Product created'], Response::HTTP_CREATED);
    }


    /**
     * @Route("/api/products/{id}", name="update_product", methods={"PUT"})
     */
    public function updateProduct(Request $request, $id): JsonResponse
    {
        $entityManager = $this->getDoctrine()->getManager();
        $product = $entityManager->getRepository(Product::class)->find($id);

        if (!$product) {
            throw $this->createNotFoundException('No product found for id '.$id);
        }

        $data = json_decode($request->getContent(), true);

        $product->setName($data['name']);
        $product->setPrice($data['price']);

        $entityManager->flush();

        return new JsonResponse(['message' => 'Product updated'], Response::HTTP_OK);
    }


    /**
     * @Route("/api/products/{id}", name="delete_product", methods={"DELETE"})
     */
    public function deleteProduct($id): JsonResponse
    {
        $entityManager = $this->getDoctrine()->getManager();
        $product = $entityManager->getRepository(Product::class)->find($id);

        if (!$product) {
            throw $this->createNotFoundException('No product found for id '.$id);
        }

        $entityManager->remove($product);
        $entityManager->flush();

        return new JsonResponse(['message' => 'Product deleted'], Response::HTTP_OK);
    }
}