<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Hateoas\Configuration\Annotation as Hateoas;
use JMS\Serializer\Annotation as Serializer;
use OpenApi\Annotations as OA;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity('email', message: "L'adresse mail est déjà utilisée")]
#[Serializer\ExclusionPolicy('ALL')]
/**
 * @OA\Schema
 *
 * @Hateoas\Relation(
 *      "self",
 *      href = @Hateoas\Route(
 *          "app_api_user_item_get",
 *          parameters = { "id" = "expr(object.getId())" },
 *          absolute = true
 *      )
 * )
 *
 * @Hateoas\Relation(
 *      "modify",
 *      href = @Hateoas\Route(
 *          "app_api_user_item_put",
 *          parameters = { "id" = "expr(object.getId())" },
 *          absolute = true
 *      )
 * )
 *
 * @Hateoas\Relation(
 *      "delete",
 *      href = @Hateoas\Route(
 *          "app_api_user_item_delete",
 *          parameters = { "id" = "expr(object.getId())" },
 *          absolute = true
 *      )
 * )
 *
 * @Hateoas\Relation(
 *     "customer",
 *     embedded = @Hateoas\Embedded("expr(object.getCustomer())")
 * )
 */
class User
{
    /**
     * @OA\Property(type="integer")
     * @var int|null
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Serializer\Expose]
    private ?int $id = null;

    /**
     * @OA\Property(type="string")
     * @var string|null
     */
    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\Length(min: 3, minMessage: 'Le nom doit contenir au moins 3 caractères')]
    #[Assert\NotBlank(message: 'Le nom est obligatoire')]
    #[Serializer\Expose]
    private ?string $lastname;

    /**
     * @OA\Property(type="string")
     * @var string|null
     */
    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\Length(min: 3, minMessage: 'Le prénom doit contenir au moins 3 caractères')]
    #[Assert\NotBlank(message: 'Le prénom est obligatoire')]
    #[Serializer\Expose]
    private ?string $firstname;

    /**
     * @OA\Property(type="string")
     * @var string|null
     */
    #[ORM\Column(type: 'string', length: 255, unique: true)]
    #[Assert\Email(message: 'L\'adresse mail est incorrecte')]
    #[Assert\NotBlank(message: 'L\'email est obligatoire')]
    #[Serializer\Expose]
    private ?string $email;

    /**
     * @var
     */
    #[ORM\ManyToOne(targetEntity: Customer::class, inversedBy: 'users')]
    private $customer;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    public function setCustomer(?Customer $customer): self
    {
        $this->customer = $customer;

        return $this;
    }
}
