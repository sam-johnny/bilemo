<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ApiUserController extends AbstractController
{
    private UserRepository $repository;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    #[Route('/api/user/index', name: 'app_api_user_index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->json(
            $this->repository->findAll(),
            200,
            [],
            ['groups' => 'user:read']);
    }
    
}
