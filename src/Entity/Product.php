<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\ORM\Mapping as ORM;
use Hateoas\Configuration\Annotation as Hateoas;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
/**
 * @Hateoas\Relation(
 *      "self",
 *      href = @Hateoas\Route(
 *          "app_api_product_item_get",
 *          parameters = { "id" = "expr(object.getId())" },
 *          absolute = true
 *      )
 * )
 *
 * @Hateoas\Relation(
 *     "authenticated_user",
 *     embedded = @Hateoas\Embedded("expr(service('security.token_storage').getToken().getUser())")
 * )
 */
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $brand;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $model;

    #[ORM\Column(type: 'float')]
    private ?float $price;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $color;

    #[ORM\Column(type: 'text')]
    private ?string $description;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $memoryStorage;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBrand(): ?string
    {
        return $this->brand;
    }

    public function setBrand(string $brand): self
    {
        $this->brand = $brand;

        return $this;
    }

    public function getModel(): ?string
    {
        return $this->model;
    }

    public function setModel(string $model): self
    {
        $this->model = $model;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(string $color): self
    {
        $this->color = $color;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getMemoryStorage(): ?string
    {
        return $this->memoryStorage;
    }

    public function setMemoryStorage(string $memoryStorage): self
    {
        $this->memoryStorage = $memoryStorage;

        return $this;
    }
}
