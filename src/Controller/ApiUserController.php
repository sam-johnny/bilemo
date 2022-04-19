<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

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
            ['groups' => 'user:index']);
    }

    #[Route('/api/user/{id}', name: 'app_api_user_show', requirements: ['id' => '[\d]+'], methods: ['GET'])]
    public function show($id): Response
    {
        return $this->json(
            $this->repository->find($id),
            200,
            [],
            ['groups' => 'user:index']
        );
    }
}
