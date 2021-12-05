<?php
namespace App\Controller;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use App\Service\ResponseService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Doctrine\DBAL\Types\Types;
use Symfony\Component\HttpFoundation\Request;
use App\Enum\ObjectStatus;
use Doctrine\ORM\EntityManagerInterface;

class CategoryController extends AbstractController
{
    public function __construct(
        private CategoryRepository $categoryRepository,
        private SerializerInterface $serializer,
        private ResponseService $responseService,
        private EntityManagerInterface $em
    )
    {
        $this->categoryRepository   = $categoryRepository;
        $this->serializer           = $serializer;
        $this->responseService      = $responseService;
        $this->em                   = $em;
    }

    #[Route('api/categories', name: 'category_list', methods:[Request::METHOD_GET])]
    public function list(): JsonResponse
    {
        return $this->json(
            $this->serializer->normalize(
                $this->categoryRepository->findAll(), Types::ARRAY, ['groups' => [Category::MY_GROUP]]
            ),
            Response::HTTP_OK
        );
    }

    #[Route('api/categories/{uuid}', name: 'category_show', methods:[Request::METHOD_GET])]
    public function show(Category $category): JsonResponse
    {
        return $this->json(
            $this->serializer->normalize($category, Types::ARRAY, ['groups' => [Category::MY_GROUP]]), 
            Response::HTTP_OK
        );
    }

    #[Route('api/categories', name: 'category_create', methods:[Request::METHOD_POST])]
    public function create(Request $request): JsonResponse
    {
        $responseArray = $this->responseService->getResponse(
            new Category(),
            ObjectStatus::Create,
            json_decode($request->getContent(), true)
        );

        return $this->json($responseArray['responseData'], $responseArray['status']);
    }

    #[Route('api/categories/{uuid}', name: 'category_update', methods:[Request::METHOD_PUT])]
    public function update(Category $category, Request $request): JsonResponse
    {
        $responseArray = $this->responseService->getResponse(
            $category,
            ObjectStatus::Update,
            json_decode($request->getContent(), true)
        );

        return $this->json($responseArray['responseData'], $responseArray['status']);
    }

    #[Route('api/categories/{uuid}', name: 'category_remove', methods:[Request::METHOD_DELETE])]
    public function remove(Category $category): JsonResponse
    {
        $this->em->remove($category);
        $this->em->flush();

        return $this->json([], Response::HTTP_OK);
    }
}
