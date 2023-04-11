<?php

namespace App\Controller;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use App\Entity\Category;
use App\Repository\CategoryRepository;

class CategoryController extends AbstractController
{
    /**
     * @Route("/api/category", name="app_category")
     */
    public function index(): Response
    {
        return $this->render('category/index.html.twig', [
            'controller_name' => 'CategoryController',
        ]);
    }


    /**
     * @Route("/api/categories", name="get_categories", methods={"GET"})
     */
    public function getCategories(Request $request, EntityManagerInterface $entityManager): JsonResponse
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
        } elseif ($sort === 'numProducts') {
            $orderBy['numProducts'] = $sortOrder === 'asc' ? 'ASC' : 'DESC';
        }

        $totalCategories = $entityManager->getRepository(Category::class)->count($criteria);
        $totalPages = ceil($totalCategories / $pageSize);
        $offset = ($page - 1) * $pageSize;

        if ($search) {
            $queryBuilder = $entityManager->createQueryBuilder();
            $queryBuilder->select('c')
                ->from(Category::class, 'c')
                ->andWhere($queryBuilder->expr()->like('c.name', ':search'))
                ->setParameter('search', '%' . $search . '%');
            $categories = $queryBuilder
                ->addOrderBy('c.name', 'ASC')
                ->setFirstResult(($page - 1) * $pageSize)
                ->setMaxResults($pageSize)
                ->getQuery()
                ->getResult();
        } else {
            $categories = $entityManager->getRepository(Category::class)->findBy($criteria, $orderBy, $pageSize, $offset);
        }

        $data = [];

        foreach ($categories as $category) {
            $data[] = [
                'id' => $category->getId(),
                'name' => $category->getName()
            ];
        }

        $paginationData = [
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'totalCategories' => $totalCategories,
            'pageSize' => $pageSize,
            'nextPage' => $page < $totalPages ? $page + 1 : null,
            'prevPage' => $page > 1 ? $page - 1 : null,
        ];

        return new JsonResponse([
            'categories' => $data,
            'pagination' => $paginationData,
        ]);
    }

    /**
     * @Route("/api/categories/{id}", name="get_category_by_id", methods={"GET"})
     */
    public function getCategoryById(Category $category): Response
    {
        return $this->json($category);
    }

    /**
     * @Route("/api/categories", name="create_category", methods={"POST"})
     */
    public function createCategory(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);

        $category = new Category();
        $category->setName($data['name']);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($category);
        $entityManager->flush();

        return $this->json($category);
    }

    /**
     * @Route("/api/categories/{id}", name="update_category", methods={"PUT"})
     */
    public function updateCategory(Request $request, $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $category = $entityManager->getRepository(Category::class)->find($id);

        if (!$category) {
            throw $this->createNotFoundException('No category found for id '.$id);
        }

        $data = json_decode($request->getContent(), true);

        $category->setName($data['name']);

        $entityManager->flush();

        return new JsonResponse(['message' => 'Category updated'], Response::HTTP_OK);
    }

    /**
     * @Route("/api/categories/{id}", name="delete_category", methods={"DELETE"})
     */
    public function deleteCategory(Category $category): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($category);
        $entityManager->flush();

        return new Response(null, Response::HTTP_NO_CONTENT);
    }
}
