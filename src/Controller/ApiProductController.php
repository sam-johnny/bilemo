<?php

namespace App\Controller;

use App\Entity\Product;
use App\Helper\Paginated\PaginatedHelper;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Annotations as OA;
use JMS\Serializer\SerializerInterface;

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


    /**
     * Collection of products
     *
     * @OA\Get(
     *     path="/api/product",
     *     tags={"product"},
     *     operationId="collectionProducts",
     *     summary="Find list of products",
     *     description="Returns a list of products",
     *     security={"bearer"},
     * ),
     *
     *  @OA\Response(
     *      response="200",
     *      description="successful operation",
     *      @OA\JsonContent(type="array", @OA\Items(ref=@Nelmio\ApiDocBundle\Annotation\Model(type=Product::class)))
     *      ),
     *
     *  @OA\Response(
     *      response="401",
     *      description="Token invalid"),
     *
     *  @OA\Response(
     *      response="404",
     *      description="Product not found"),
     *
     * @param PaginatedHelper $paginatedHelper
     * @param Request $request
     * @return Response
     */
    #[Route(name: 'app_api_product_collection_get', methods: ['GET'])]
    public function collectionProducts(PaginatedHelper $paginatedHelper, Request $request): Response
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

    /**
     * Find product by ID
     *
     * @OA\Get(
     *     path="/api/product/{id}",
     *     tags={"product"},
     *     operationId="itemProduct",
     *     summary="Find product by ID",
     *     description="Returns product",
     *     security={"bearer"},
     *     @OA\Parameter(
     *      name="id",
     *      in="path",
     *      description="ID of product to return",
     *      required=true,
     *     @OA\Schema(
     *      type="integer",
     *      format="int64"
     *     ),
     *  )
     * ),
     *
     *  @OA\Response(
     *      response="200",
     *      description="successful operation",
     *      @OA\JsonContent(type="array", @OA\Items(ref=@Nelmio\ApiDocBundle\Annotation\Model(type=Product::class)))
     *      ),
     *
     *  @OA\Response(
     *      response="401",
     *      description="Token invalid"),
     *
     *  @OA\Response(
     *      response="404",
     *      description="Product not found"),
     *
     *
     *
     * @param Product $product
     * @return Response
     */
    #[Route('/{id}', name: 'app_api_product_item_get', methods: ['GET'])]
    public function itemProduct(Product $product): Response
    {
        $productJson = $this->serializer->serialize($product, 'json');

        return new Response(
            $productJson,
            Response::HTTP_OK,
            ['Content-Type' => 'application/json']
        );
    }
}
