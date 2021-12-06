<?php
namespace App\Controller\Api;

use App\Entity\ProductProperty;
use App\Service\ResponseService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Doctrine\DBAL\Types\Types;
use Symfony\Component\HttpFoundation\Request;
use App\Enum\ObjectStatus;
use App\Repository\ProductPropertyRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;

class ProductPropertyController extends AbstractController
{
    public function __construct(
        private ProductPropertyRepository $productPropertyRepository,
        private ProductRepository $productRepository,
        private SerializerInterface $serializer,
        private ResponseService $responseService,
        private EntityManagerInterface $em
    )
    {
        $this->productPropertyRepository    = $productPropertyRepository;
        $this->productRepository    = $productRepository;
        $this->serializer           = $serializer;
        $this->responseService      = $responseService;
        $this->em                   = $em;
    }

    #[Route('api/product-properties', name: 'product_properties_list', methods:[Request::METHOD_GET])]
    public function list(): JsonResponse
    {
        return $this->json(
            $this->serializer->normalize(
                $this->productPropertyRepository->findAll(), Types::ARRAY, ['groups' => [ProductProperty::MY_GROUP]]
            ),
            Response::HTTP_OK
        );
    }

    #[Route('api/product-properties/{uuid}', name: 'product_properties_show', methods:[Request::METHOD_GET])]
    public function show(ProductProperty $productProperty): JsonResponse
    {
        return $this->json(
            $this->serializer->normalize($productProperty, Types::ARRAY, ['groups' => [ProductProperty::MY_GROUP]]), 
            Response::HTTP_OK
        );
    }

    #[Route('api/product-properties', name: 'product_properties_create', methods:[Request::METHOD_POST])]
    public function create(Request $request): JsonResponse
    {
        $product = $this->productRepository->findOneActualProduct(
                json_decode($request->getContent(), true)['product'] ?? null
        );

        if(null === $product) {
            return $this->json("Syntax error", 404);
        }

        $productProperty = (new ProductProperty())->setProduct($product);
        $product->setProductProperty($productProperty);

        $responseArray = $this->responseService->getResponse(
            $productProperty,
            ObjectStatus::Create,
            json_decode($request->getContent(), true)
        );

        return $this->json($responseArray['responseData'], $responseArray['status']);
    }

    #[Route('api/product-properties/{uuid}', name: 'product_properties_update', methods:[Request::METHOD_PUT])]
    public function update(ProductProperty $productProperty, Request $request): JsonResponse
    {
        $dataJson       = json_decode($request->getContent(), true);
        $productUuid    = $dataJson['product'] ?? null;
        $product        = null;

        if(null === $productUuid) {
            $product = $productProperty->getProduct();
        } else {
            $product = $this->productRepository->findOneActualProduct($productUuid);
        }

        if(null === $product) {
            return $this->json("Syntax error", ObjectStatus::Error->value);
        }

        $dataJson['product'] = $product->getIdentifier();

        $responseArray = $this->responseService->getResponse(
            $productProperty->setProduct($product),
            ObjectStatus::Update,
            $dataJson
        );

        return $this->json($responseArray['responseData'], $responseArray['status']);
    }

    #[Route('api/product-properties/{uuid}', name: 'product_properties_remove', methods:[Request::METHOD_DELETE])]
    public function remove(ProductProperty $productProperty): JsonResponse
    {
        $this->em->remove($productProperty);
        $this->em->flush();

        return $this->json([], Response::HTTP_OK);
    }
}
