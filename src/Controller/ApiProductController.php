<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ApiProductController extends AbstractController
{
    private ProductRepository $repository;

    public function __construct(ProductRepository $repository)
    {
        $this->repository = $repository;
    }

    #[Route('/api/product/index', name: 'app_api_product_index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->json([
            $this->repository->findAll(),
            200
        ]);
    }

    #[Route('/api/product/{id}', name: 'app_api_product_show', requirements: ['id' => '[\d]+'],methods: ['GET'])]
    public function show($id): Response
    {
        return $this->json([
            $this->repository->find($id),
            200
        ]);
    }
}
