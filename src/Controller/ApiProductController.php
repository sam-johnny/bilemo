<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/product')]
class ApiProductController extends AbstractController
{
    private ProductRepository $repository;
    private SerializerInterface $serializer;

    public function __construct(
        ProductRepository   $repository,
        SerializerInterface $serializer
    )
    {
        $this->repository = $repository;
        $this->serializer = $serializer;
    }

    #[Route(name: 'app_api_product_collection_get', methods: ['GET'])]
    public function collection(): Response
    {
        $product = $this->repository->findAll();
        $json = $this->serializer->serialize($product, 'json');

        return new Response(
            $json,
            Response::HTTP_OK,
            ['Content-Type' => 'application/json']
        );
    }

    #[Route('/{id}', name: 'app_api_product_item_get', requirements: ['id' => '[\d]+'], methods: ['GET'])]
    public function item(int $id): Response
    {
        $post = $this->repository->find($id);
        $json = $this->serializer->serialize($post, 'json');

        return new Response(
            $json,
            Response::HTTP_OK,
            ['Content-Type' => 'application/json']
        );
    }
}
