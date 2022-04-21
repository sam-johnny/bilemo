<?php

namespace App\Controller;

use App\Helper\Paginated\PaginatedHelper;
use App\Repository\ProductRepository;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/product')]
class ApiProductController extends AbstractController
{
    private ProductRepository $repository;
    private SerializerInterface $serializer;

    public function __construct(ProductRepository $repository, SerializerInterface $serializer)
    {
        $this->repository = $repository;
        $this->serializer = $serializer;
    }

    #[Route(name: 'app_api_product_collection_get', methods: ['GET'])]
    public function collection(PaginatedHelper $paginatedHelper, Request $request): Response
    {

        $paginatedCollection = $paginatedHelper->paginatedCollection(
            $this->repository->findAll(),
            $request->attributes->get('_route')
        );

        $product = $this->serializer->serialize($paginatedCollection, 'json');

        return new Response(
            $product,
            Response::HTTP_OK,
            ['Content-Type' => 'application/json']
        );
    }

    #[Route('/{id}', name: 'app_api_product_item_get', requirements: ['id' => '[\d]+'], methods: ['GET'])]
    public function item(int $id): Response
    {
        $json = $this->serializer->serialize($this->repository->find($id), 'json');

        return new Response(
            $json,
            Response::HTTP_OK,
            ['Content-Type' => 'application/json']
        );
    }
}
