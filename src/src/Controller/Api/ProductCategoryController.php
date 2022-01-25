<?php
namespace App\Controller\Api;

use App\Entity\ProductCategory;
use App\Repository\CategoryRepository;
use App\Repository\ProductCategoryRepository;
use App\Repository\ProductRepository;
use App\Service\Factory\Builder\ProductCategoryBuilder;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use App\Service\ResponseService;

class ProductCategoryController extends AbstractController
{
    public function __construct(
        private ProductCategoryRepository $productCategoryRepository,
        private ProductRepository $productRepository,
        private CategoryRepository $categoryRepository,
        private SerializerInterface $serializer,
        private ResponseService $responseService,
        private EntityManagerInterface $em
    )
    {
        $this->productCategoryRepository    = $productCategoryRepository;
        $this->productRepository    = $productRepository;
        $this->categoryRepository    = $categoryRepository;
        $this->serializer           = $serializer;
        $this->responseService      = $responseService;
        $this->em                   = $em;
    }

    #[Route('api/product-categories', name:'product_categories_list', methods:[Request::METHOD_GET])]
    public function list(): JsonResponse
    {
        return $this->json(
            $this->serializer->normalize($this->productCategoryRepository->findAll(), Types::ARRAY, ['groups' => [ProductCategory::MY_GROUP]]), 
            Response::HTTP_OK
        );
    }

    #[Route('api/product-categories/{uuid}', name:'product_categories_show', methods:[Request::METHOD_GET])]
    public function show(ProductCategory $productCategory): JsonResponse
    {
        return $this->json(
            $this->serializer->normalize($productCategory, Types::ARRAY, ['groups' => [ProductCategory::MY_GROUP]]), 
            Response::HTTP_OK
        );
    }

    #[Route('api/product-categories', name:'product_categories_create', methods:[Request::METHOD_POST])]
    public function create(Request $request): JsonResponse
    {
        $dataArray  = json_decode($request->getContent(), true);
        $product    = $this->productRepository->findOneBy(['uuid' => $dataArray['product'] ?? null]);
        $category   = $this->categoryRepository->findOneBy(['uuid' => $dataArray['category'] ?? null]);

        if(null === $product || null === $category) {
            return $this->json("Syntax error", 404);
        }

        $productCategory = 
            ProductCategoryBuilder::create()
                ->setProduct($product)
                ->setCategory($category);

        $this->em->persist($productCategory);
        $this->em->flush();

        return $this->json(
            $this->serializer->normalize($productCategory, Types::ARRAY, ['groups' => [ProductCategory::MY_GROUP]]), 
            Response::HTTP_OK
        );
    }

    #[Route('api/product-categories/{uuid}', name:'product_categories_update', methods:[Request::METHOD_PUT])]
    public function update(Request $request, ProductCategory $productCategory): JsonResponse
    {
        if(!isset(json_decode($request->getContent(), true)['product']) && !isset(json_decode($request->getContent(), true)['category'])) {
            return $this->json("Syntax error", 404);
        }

        $dataArray      = json_decode($request->getContent(), true);
        $productID      = $dataArray['product'] ?? null;
        $categoryID     = $dataArray['category'] ?? null;

        $product    = (null === $productID) ? $productCategory->getProduct() : $this->productRepository->findOneBy(['uuid' => $productID]);
        $category   = (null === $categoryID) ? $productCategory->getCategory() : $this->categoryRepository->findOneBy(['uuid' => $categoryID]);

        $productCategory->setProduct($product)->setCategory($category);

        $this->em->persist($productCategory);
        $this->em->flush();
     
        return $this->json(
            $this->serializer->normalize($productCategory, Types::ARRAY, ['groups' => [ProductCategory::MY_GROUP]]), 
            Response::HTTP_OK
        );
    }

    #[Route('api/product-categories/{uuid}', name: 'product_categories_remove', methods:[Request::METHOD_DELETE])]
    public function remove(ProductCategory $productCategory): JsonResponse
    {
        $this->em->remove($productCategory);
        $this->em->flush();

        return $this->json([], Response::HTTP_OK);
    }
}