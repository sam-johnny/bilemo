<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups("user:index")]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups("user:index")]
    #[Assert\Length(min: 3, minMessage: 'Le nom doit contenir au moins 3 caractères')]
    #[Assert\NotBlank(message: 'Le nom est obligatoire')]
    private $lastname;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups("user:index")]
    #[Assert\Length(min: 3, minMessage: 'Le prénom doit contenir au moins 3 caractères')]
    #[Assert\NotBlank(message: 'Le prénom est obligatoire')]
    private $firstname;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups("user:index")]
    #[Assert\Email(message: 'L\'adresse mail est incorrecte')]
    #[Assert\NotBlank(message: 'L\'email est obligatoire')]
    private $email;

    #[ORM\ManyToOne(targetEntity: Customer::class, inversedBy: 'users')]
    #[Groups("user:index")]
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
