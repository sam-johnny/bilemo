<?php

namespace App\Controller;

use App\Entity\User;
use App\Helper\Paginated\PaginatedHelper;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;


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
     * @param PaginatedHelper $paginatedHelper
     * @param Request $request
     * @return Response
     *
     * @OA\Response(
     *     response=200,
     *     description="Returns a list of users",
     *     @OA\JsonContent(
     *     type="array",
     *     @OA\Items(ref=@Model(type=User::class))
     *    )
     * )
     *
     * @OA\Tag(name="User")
     * @Security(name="Bearer")
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
     * @param User $user
     * @return Response
     *
     * @OA\Response(
     *     response=200,
     *     description="Returns a item of user",
     *     @OA\JsonContent(
     *     type="array",
     *     @OA\Items(ref=@Model(type=User::class))
     *    )
     * )
     *
     * @OA\Tag(name="User")
     * @Security(name="Bearer")
     */
    #[Route('/{id}', name: 'app_api_user_item_get', methods: ['GET'])]
    public function item(User $user): Response
    {
        $userJson = $this->serializer->serialize($user, 'json');

        return new Response(
            $userJson,
            Response::HTTP_OK,
            ['Content-Type' => 'application/json']
        );
    }

    /**
     * @param Request $request
     * @param ValidatorInterface $validator
     * @return JsonResponse|Response
     *
     * @OA\Response(
     *     response=201,
     *     description="Create new user",
     *     @OA\JsonContent(
     *     type="array",
     *     @OA\Items(ref=@Model(type=User::class))
     *    )
     * )
     *
     * @OA\Tag(name="User")
     * @Security(name="Bearer")
     */
    #[Route(name: 'app_api_user_item_post', methods: ['POST'])]
    public function user(
        Request            $request,
        ValidatorInterface $validator
    ): JsonResponse|Response
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
                Response::HTTP_CREATED
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
     * @param User $user
     * @return Response
     *
     * @OA\Response(
     *     response=204,
     *     description="Delete user",
     * )
     *
     * @OA\Tag(name="User")
     * @Security(name="Bearer")
     */
    #[Route('/{id}', name: 'app_api_user_item_delete', methods: ['DELETE'])]
    public function delete(User $user): Response
    {
        $this->entityManager->remove($user);
        $this->entityManager->flush();

        return new Response(null, Response::HTTP_NO_CONTENT);
    }
}
