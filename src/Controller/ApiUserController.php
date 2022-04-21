<?php

namespace App\Controller;

use App\Entity\User;
use App\Helper\Paginated\PaginatedHelper;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;

use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/user')]
class ApiUserController extends AbstractController
{
    private UserRepository $repository;
    private EntityManagerInterface $entityManager;
    private SerializerInterface $serializer;

    public function __construct(
        UserRepository         $repository,
        EntityManagerInterface $entityManager,
        SerializerInterface    $serializer
    )
    {
        $this->repository = $repository;
        $this->entityManager = $entityManager;
        $this->serializer = $serializer;
    }


    /**
     * @return Response
     */
    #[Route(name: 'app_api_user_collection_get', methods: ['GET'])]
    public function collection(
        PaginatedHelper $paginatedHelper,
        Request         $request
    ): Response
    {

        $paginatedCollection = $paginatedHelper->paginatedCollection(
            $this->repository->findAll(),
            $request->attributes->get('_route')
        );

        $user = $this->serializer->serialize($paginatedCollection, 'json');

        return new Response(
            $user,
            Response::HTTP_OK,
            ['Content-Type' => 'application/json'],
        );
    }

    /**
     * @param int $id
     * @return Response
     */
    #[Route('/{id}', name: 'app_api_user_item_get', requirements: ['id' => '[\d]+'], methods: ['GET'])]
    public function item(int $id): Response
    {
        $user = $this->serializer->serialize($this->repository->find($id), 'json');

        return new Response(
            $user,
            Response::HTTP_OK,
            ['Content-Type' => 'application/json']
        );
    }

    /**
     * @param Request $request
     * @param ValidatorInterface $validator
     * @param UrlGeneratorInterface $urlGenerator
     * @return Response
     */
    #[Route(name: 'app_api_user_item_post', methods: ['POST'])]
    public function user(
        Request               $request,
        ValidatorInterface    $validator,
        UrlGeneratorInterface $urlGenerator
    ): Response
    {
        try {
            /** @var User $user */
            $user = $this->serializer->deserialize($request->getContent(), User::class, 'json');

            $errors = $validator->validate($user);

            if (count($errors) > 0) {
                return $this->json($errors, Response::HTTP_BAD_REQUEST);
            }

            $this->entityManager->persist($user);
            $this->entityManager->flush();

            return new Response(
                null,
                Response::HTTP_CREATED,
                ["Location" => $urlGenerator->generate('app_api_user_item_get', ['id' => $user->getId()])]
            );
        } catch
        (NotEncodableValueException $e) {
            return $this->json([
                'status' => 400,
                'message' => $e->getMessage()
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * @param $id
     * @return Response
     */
    #[Route('/{id}', name: 'app_api_user_item_delete', methods: ['DELETE'])]
    public function delete($id): Response
    {
        $user = $this->repository->find($id);
        $this->entityManager->remove($user);
        $this->entityManager->flush();

        return new Response(null, Response::HTTP_NO_CONTENT);
    }
}
