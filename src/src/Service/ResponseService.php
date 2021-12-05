<?php
namespace App\Service;

use App\Entity\Category;
use App\Entity\Product;
use App\Entity\ProductProperty;
use App\Helper\ResponceServiceHelper;
use App\Service\Factory\EntityFactory;
use Doctrine\ORM\EntityManagerInterface;
use App\Enum\ResponseType;
use App\Enum\ObjectStatus;

class ResponseService
{
    public function __construct(
        private EntityManagerInterface $em,
        private object $type = ResponseType::Undefined,
        private array $requestData = [],
        private ?object $errorMessage = null,
    )
    {
        $this->em = $em;
        $this->type = $type;
        $this->requestData = $requestData;
        $this->errorMessage = $errorMessage;
    }

    public function getResponse(object $entity, ObjectStatus $status, array $data): array
    {
        $this->setTypeByEntity($entity);

        $this->requestData  = (null !== $data) ? $data : [];
        $this->errorMessage = ResponceServiceHelper::dataValidate($this->type, $this->requestData);

        $responseArray  = [
            'responseData'  => [$this->errorMessage?->value],
            'status'        => ObjectStatus::Error->value,
            'groups'        => []
        ];

        if (null === $this->errorMessage) {
            $responseArray['status']            = $status->value;
            $responseArray['groups']            = ['groups' => [$entity::MY_GROUP]];
            $responseArray['responseData']      = $this->entitySave($this->getBuilderByType()->build($entity, $this->requestData));
        }

        return $responseArray;
    }

    private function setTypeByEntity(object $entity): void
    {
        match (get_class($entity)) {
            Category::class         => $this->type = ResponseType::CategoryType,
            Product::class          => $this->type = ResponseType::ProductType,
            ProductProperty::class  => $this->type = ResponseType::ProductPropertyType,
            default                 => $this->type = ResponseType::Undefined
        };
    }

    private function getBuilderByType()
    {
        return match ($this->type) {
            ResponseType::CategoryType          => EntityFactory::createCategory(),
            ResponseType::ProductType           => EntityFactory::createProduct(),
            ResponseType::ProductPropertyType   => EntityFactory::createProductProperty(),
        };
    }

    private function entitySave(object $entity)
    {
        $this->em->persist($entity);
        $this->em->flush();

        return $entity;
    }
}