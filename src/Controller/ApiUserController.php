<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/user')]
class ApiUserController extends AbstractController
{
    private UserRepository $repository;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    #[Route(name: 'app_api_user_collection_get', methods: ['GET'])]
    public function collection(): Response
    {
        return $this->json(
            $this->repository->findAll(),
            Response::HTTP_OK,
            [],
            ['groups' => 'user:index']);
    }

    #[Route('/{id}', name: 'app_api_user_item_get', requirements: ['id' => '[\d]+'], methods: ['GET'])]
    public function item(): Response
    {
        return $this->json(
            $this->repository->findOneBy([]),
            Response::HTTP_OK,
            [],
            ['groups' => 'user:index']
        );
    }

    #[Route(name: 'app_api_user_item_post', methods: ['POST'])]
    public function user(
        SerializerInterface    $serializer,
        Request                $request,
        ValidatorInterface     $validator,
        EntityManagerInterface $entityManager,
        UrlGeneratorInterface  $urlGenerator
    ): Response
    {
        try {
            /** @var User $user */
            $user = $serializer->deserialize($request->getContent(), User::class, 'json');

            $errors = $validator->validate($user);

            if (count($errors) > 0) {
                return $this->json($errors, Response::HTTP_BAD_REQUEST);
            }

            $user->setCustomer($this->getUser());
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->json(
                $user,
                Response::HTTP_CREATED,
                ["Location" => $urlGenerator->generate('app_api_user_item_get', ['id' => $user->getId()])],
                ['groups' => 'user:index']
            );
        } catch
        (NotEncodableValueException $e) {
            return $this->json([
                'status' => 400,
                'message' => $e->getMessage()
            ], Response::HTTP_BAD_REQUEST);
        }
    }
}
