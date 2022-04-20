<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/product')]
class ApiProductController extends AbstractController
{
    private ProductRepository $repository;

    public function __construct(ProductRepository $repository)
    {
        $this->repository = $repository;
    }

    #[Route(name: 'app_api_product_collection_get', methods: ['GET'])]
    public function collection(): Response
    {
        return $this->json(
            $this->repository->findAll(),
            Response::HTTP_OK,
            []
        );
    }

    #[Route('/{id}', name: 'app_api_product_item_get', requirements: ['id' => '[\d]+'],methods: ['GET'])]
    public function item(): Response
    {
        return $this->json(
            $this->repository->findOneBy([]),
            Response::HTTP_OK,
            []
        );
    }
}
