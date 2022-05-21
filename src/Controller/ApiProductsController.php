<?php

namespace App\Controller;

use App\Entity\Product;
use App\Helper\Paginated\PaginatedHelper;
use App\Repository\ProductRepository;
use Nelmio\ApiDocBundle\Annotation\Security;
use Psr\Cache\InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Annotations as OA;
use JMS\Serializer\SerializerInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

#[Route('/api/products')]
class ApiProductsController extends AbstractController
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
     *     path="/api/products",
     *     operationId="collectionProducts",
     *     summary="Find list of products",
     *     description="Returns a list of products"
     * ),
     *
     * @OA\Response(
     *      response="200",
     *      description="successful operation",
     *      @OA\JsonContent(type="array", @OA\Items(ref=@Nelmio\ApiDocBundle\Annotation\Model(type=Product::class)))
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
     * @OA\Tag(name="products")
     * @Security(name="Bearer")
     *
     * @param PaginatedHelper $paginatedHelper
     * @param Request $request
     * @param CacheInterface $cache
     * @return Response
     * @throws InvalidArgumentException
     */
    #[Route(name: 'app_api_product_collection_get', methods: ['GET'])]
    public function collectionProducts(
        PaginatedHelper $paginatedHelper,
        Request $request,
        CacheInterface $cache
    ): Response
    {

        $paginatedCollection = $paginatedHelper->paginatedCollection(
            $this->repository->findAll(),
            $request->attributes->get('_route')
        );

        $product = $cache->get('products_list', function (ItemInterface $item) use ($paginatedCollection) {
            $item->expiresAfter(3600);
            return $this->serializer->serialize($paginatedCollection, 'json');
        } );

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
     *     path="/api/products/{id}",
     *     operationId="itemProduct",
     *     summary="Find product by ID",
     *     description="Returns product",
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
     * @OA\Response(
     *      response="200",
     *      description="successful operation",
     *      @OA\JsonContent(type="array", @OA\Items(ref=@Nelmio\ApiDocBundle\Annotation\Model(type=Product::class)))
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
     * @OA\Tag(name="products")
     * @Security(name="Bearer")
     *
     * @param Product $product
     * @param CacheInterface $cache
     * @return Response
     * @throws InvalidArgumentException
     */
    #[Route('/{id}', name: 'app_api_product_item_get', methods: ['GET'])]
    public function itemProduct(
        Product $product,
        CacheInterface $cache
    ): Response
    {
        $product = $cache->get('product_item'. $product->getId(), function (ItemInterface $item) use ($product){
            $item->expiresAfter(3600);
            return $this->serializer->serialize($product, 'json');
        });

         return new Response(
            $product,
            Response::HTTP_OK,
            ['Content-Type' => 'application/json']
        );

    }
}
