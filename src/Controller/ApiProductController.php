<?php

namespace App\Controller;

use App\Entity\Product;
use App\Helper\Paginated\PaginatedHelper;
use App\Repository\ProductRepository;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;

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
     * @OA\Response(
     *     response=200,
     *     description="Returns a list of products",
     *     @OA\JsonContent(
     *     type="array",
     *     @OA\Items(ref=@Model(type=Product::class))
     *    )
     * )
     *
     * @OA\Tag(name="Product")
     * @Security(name="Bearer")
     */
    #[Route(name: 'app_api_product_collection_get', methods: ['GET'])]
    public function collection(PaginatedHelper $paginatedHelper, Request $request): Response
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

    #[Route('/{id}', name: 'app_api_product_item_get', methods: ['GET'])]
    /**
     * @OA\Response(
     *     response=200,
     *     description="Returns an item of product",
     *     @OA\JsonContent(
     *     type="array",
     *     @OA\Items(ref=@Model(type=Product::class))
     *    )
     * )
     *
     * @OA\Tag(name="Product")
     * @Security(name="Bearer")
     */
    public function item(Product $product): Response
    {
        $productJson = $this->serializer->serialize($product, 'json');

        return new Response(
            $productJson,
            Response::HTTP_OK,
            ['Content-Type' => 'application/json']
        );
    }
}
