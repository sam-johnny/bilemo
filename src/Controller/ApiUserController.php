<?php

namespace App\Controller;

use App\Entity\User;
use App\Exception\CustomerInvalidException;
use App\Helper\Paginated\PaginatedHelper;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Cache;
use Psr\Cache\InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use OpenApi\Annotations as OA;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\Serializer\SerializerInterface as SerializerSymfony;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;


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
     * Collection of users
     *
     * @OA\Get(
     *     path="/api/user",
     *     tags={"user"},
     *     operationId="collectionUsers",
     *     summary="Find list of users",
     *     description="Returns a list of users",
     *     security={"bearer"}
     * ),
     *
     * @OA\Response(
     *      response="200",
     *      description="successful operation",
     *      @OA\JsonContent(type="array", @OA\Items(ref=@Nelmio\ApiDocBundle\Annotation\Model(type=User::class)))
     *      ),
     *
     * @OA\Response(
     *      response="401",
     *      description="Token invalid"),
     *
     * @OA\Response(
     *      response="404",
     *      description="Product not found"),
     *
     * @param PaginatedHelper $paginatedHelper
     * @param Request $request
     * @param CacheInterface $cache
     * @return JsonResponse
     * @throws InvalidArgumentException
     */
    #[Route(name: 'app_api_user_collection_get', methods: ['GET'])]
    public function collectionUsers(
        PaginatedHelper $paginatedHelper,
        Request         $request,
        CacheInterface  $cache
    ): JsonResponse
    {

        $paginatedCollection = $paginatedHelper->paginatedCollection(
            $this->repository->findAll(),
            $request->attributes->get('_route')
        );

        $users = $cache->get('users_list', function (ItemInterface $item) use ($paginatedCollection) {
            $item->expiresAfter(3600);
            return $this->serializer->serialize($paginatedCollection, 'json');
        });

        return new JsonResponse(
            $users,
            Response::HTTP_OK,
            [],
            true
        );
    }

    /**
     * Find user by ID
     *
     * @OA\Get(
     *     path="/api/user/{id}",
     *     tags={"user"},
     *     operationId="itemUsers",
     *     summary="Find user by ID",
     *     description="Returns a single user",
     *     security={"bearer"},
     *     @OA\Parameter(
     *      name="id",
     *      in="path",
     *      description="ID of user to return",
     *      required=true,
     *     @OA\Schema(
     *      type="integer",
     *      format="int64"
     *     ),
     *  )
     * ),
     *
     * @OA\Response(
     *      response="200",
     *      description="successful operation",
     *      @OA\JsonContent(type="array", @OA\Items(ref=@Nelmio\ApiDocBundle\Annotation\Model(type=User::class)))
     *      ),
     *
     * @OA\Response(
     *      response="401",
     *      description="Token invalid"),
     *
     * @OA\Response(
     *      response="404",
     *      description="Product not found"),
     *
     * @param User $user
     * @param CacheInterface $cache
     * @return JsonResponse
     * @throws InvalidArgumentException
     */
    #[Route('/{id}', name: 'app_api_user_item_get', methods: ['GET'])]
    public function itemUser(
        User $user,
        CacheInterface $cache
    ): JsonResponse
    {
        $user = $cache->get('user_item'. $user->getId(), function (ItemInterface $item) use ($user) {
            $item->expiresAfter(3600);
            return $this->serializer->serialize($user, 'json');
        });

        return new JsonResponse(
            $user,
            Response::HTTP_OK,
            [],
            true
        );
    }

    /**
     * Add new user to the list
     *
     * @OA\Post(
     *     path="/api/user",
     *     tags={"user"},
     *     operationId="addUser",
     *     summary="Add new user",
     *     description="Add new user",
     *     security={"bearer"},
     *      @OA\Parameter(
     *      name="id",
     *      in="path",
     *      description="ID of user to return",
     *      required=true,
     *     @OA\Schema(
     *      type="integer",
     *      format="int64"
     *     ),
     *  )
     * ),
     *
     * @OA\Response(
     *      response="201",
     *      description="successful addition",
     *      @OA\JsonContent(type="array", @OA\Items(ref=@Nelmio\ApiDocBundle\Annotation\Model(type=User::class)))
     *      ),
     *
     * @OA\Response(
     *      response="401",
     *      description="Token invalid"),
     *
     * @OA\Response(
     *      response="404",
     *      description="Product not found"),
     *
     * @OA\Response(
     *      response="500",
     *      description="Malformed JSON"),
     *
     * @param Request $request
     * @param ValidatorInterface $validator
     * @param UrlGeneratorInterface $urlGenerator
     * @return JsonResponse
     */
    #[Route(name: 'app_api_add_user_item_post', methods: ['POST'])]
    public function addUser(
        Request               $request,
        ValidatorInterface    $validator,
        UrlGeneratorInterface $urlGenerator
    ): JsonResponse
    {
        /** @var User $user */
        $user = $this->serializer->deserialize($request->getContent(), User::class, 'json');

        $errors = $validator->validate($user);

        if (count($errors) > 0) {
            return new JsonResponse(
                $this->serializer->serialize($errors, 'json'),
                Response::HTTP_BAD_REQUEST,
                [],
                true
            );
        }

        $this->entityManager->persist($user);
        $this->entityManager->flush();


        return new JsonResponse(
            $this->serializer->serialize($user, 'json'),
            Response::HTTP_CREATED,
            ["Location" => $urlGenerator->generate("app_api_user_item_get", ["id" => $user->getId()])],
            true
        );


    }

    /**
     * Update an existing user
     *
     * @OA\Put(
     *     path="/api/user/{id}",
     *     tags={"user"},
     *     operationId="updateUser",
     *     summary="Update user",
     *     description="Update an existing user",
     *     security={"bearer"}
     * ),
     *
     * @OA\Response(
     *      response="200",
     *      description="successful addition",
     *      @OA\JsonContent(type="array", @OA\Items(ref=@Nelmio\ApiDocBundle\Annotation\Model(type=User::class)))
     *      ),
     *
     * @OA\Response(
     *      response="401",
     *      description="Token invalid"),
     *
     * * @OA\Response(
     *      response="400",
     *      description="Customer invalid"),
     *
     * @OA\Response(
     *      response="404",
     *      description="Product not found"),
     *
     * @OA\Response(
     *      response="500",
     *      description="Malformed JSON"),
     *
     * @param User $user
     * @param Request $request
     * @param ValidatorInterface $validator
     * @param SerializerSymfony $serializer
     * @param Security $security
     * @return JsonResponse
     * @throws CustomerInvalidException
     */
    #[Route('/{id}', name: 'app_api_user_item_put', methods: ['PUT'])]
    public function updateUser(
        User               $user,
        Request            $request,
        ValidatorInterface $validator,
        SerializerSymfony  $serializer,
        Security           $security
    ): JsonResponse
    {

        if ($user->getCustomer() !== $security->getUser()){
            throw new CustomerInvalidException("Customer invalid");
        }

        $serializer->deserialize(
            $request->getContent(),
            User::class,
            'json',
            ['object_to_populate' => $user]
        );

        $error = $validator->validate($user);

        if (count($error) > 0) {
            return new JsonResponse(
                $this->serializer->serialize($error, 'json'),
                Response::HTTP_BAD_REQUEST,
                [],
                true
            );
        }

        $this->entityManager->flush();

        return new JsonResponse(
            $this->serializer->serialize($user, 'json'),
            Response::HTTP_OK,
            [],
            true
        );
    }

    /**
     * Delete user to the list
     *
     * @OA\Delete(
     *     path="/api/user/{id}",
     *     tags={"user"},
     *     operationId="deleteUser",
     *     summary="Delete user",
     *     description="delete user",
     *     security={"bearer"},
     *     @OA\Parameter(
     *      name="id",
     *      in="path",
     *      description="ID of user to return",
     *      required=true,
     *     @OA\Schema(
     *      type="integer",
     *      format="int64"
     *     ),
     *  )
     * ),
     *
     * @OA\Response(
     *      response="204",
     *      description="Delete successful",
     *      @OA\JsonContent(type="array", @OA\Items(ref=@Nelmio\ApiDocBundle\Annotation\Model(type=User::class)))
     *      ),
     *
     * @OA\Response(
     *      response="401",
     *      description="Token invalid"),
     *
     * @OA\Response(
     *      response="404",
     *      description="Product not found"),
     *
     * @param User $user
     * @param Security $security
     * @return Response
     * @throws CustomerInvalidException
     */
    #[Route('/{id}', name: 'app_api_user_item_delete', methods: ['DELETE'])]
    public function deleteUser(
        User $user,
        Security $security
    ): Response
    {
        if ($user->getCustomer() !== $security->getUser()){
            throw new CustomerInvalidException("Customer invalid");
        }

        $this->entityManager->remove($user);
        $this->entityManager->flush();

        return new Response(null, Response::HTTP_NO_CONTENT);
    }
}
