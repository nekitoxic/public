<?php
namespace App\Controller\Api;

use App\Entity\Product;
use App\Repository\ProductRepository;
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

class ProductController extends AbstractController
{
    public function __construct(
        private ProductRepository $productRepository,
        private SerializerInterface $serializer,
        private ResponseService $responseService,
        private EntityManagerInterface $em
    )
    {
        $this->productRepository    = $productRepository;
        $this->serializer           = $serializer;
        $this->responseService      = $responseService;
        $this->em                   = $em;
    }

    #[Route('api/products', name: 'product_list', methods:[Request::METHOD_GET])]
    public function list(): JsonResponse
    {
        return $this->json(
            $this->serializer->normalize(
                $this->productRepository->findAll(), Types::ARRAY, ['groups' => [Product::MY_GROUP]]
            ),
            Response::HTTP_OK
        );
    }

    #[Route('api/products/{uuid}', name: 'product_show', methods:[Request::METHOD_GET])]
    public function show(Product $product): JsonResponse
    {
        return $this->json(
            $this->serializer->normalize($product, Types::ARRAY, ['groups' => [Product::MY_GROUP]]), 
            Response::HTTP_OK
        );
    }

    #[Route('api/products', name: 'product_create', methods:[Request::METHOD_POST])]
    public function create(Request $request): JsonResponse
    {
        $responseArray = $this->responseService->getResponse(
            new Product(),
            ObjectStatus::Create,
            json_decode($request->getContent(), true)
        );

        return $this->json($responseArray['responseData'], $responseArray['status']);
    }

    #[Route('api/products/{uuid}', name: 'product_update', methods:[Request::METHOD_PUT])]
    public function update(Product $product, Request $request): JsonResponse
    {
        $responseArray = $this->responseService->getResponse(
            $product,
            ObjectStatus::Update,
            json_decode($request->getContent(), true)
        );

        return $this->json($responseArray['responseData'], $responseArray['status']);
    }

    #[Route('api/products/{uuid}', name: 'product_remove', methods:[Request::METHOD_DELETE])]
    public function remove(Product $product): JsonResponse
    {
        $this->em->remove($product);
        $this->em->flush();

        return $this->json([], Response::HTTP_OK);
    }
}
