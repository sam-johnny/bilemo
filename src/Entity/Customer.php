<?php

namespace App\Entity;

use App\Repository\CustomerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\Pure;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use JMS\Serializer\Annotation as Serializer;
use OpenApi\Annotations as OA;

#[ORM\Entity(repositoryClass: CustomerRepository::class)]
#[Serializer\ExclusionPolicy('ALL')]
/**
 * @OA\Schema
 */
class Customer implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @OA\Property(type="integer")
     * @var int|null
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Serializer\Expose]
    private ?int $id;

    /**
     * @OA\Property(type="string")
     * @var string|null
     */
    #[ORM\Column(type: 'string', length: 255)]
    #[Serializer\Expose]
    private ?string $name;

    /**
     * @OA\Property(type="string")
     * @var string|null
     */
    #[ORM\Column(type: 'string', length: 255, unique: true)]
    #[Serializer\Expose]
    private ?string $email;

    /**
     * @OA\Property(type="string")
     * @var string[]
     */
    #[ORM\Column(type: 'json')]
    private array $roles = [];

    /**
     * @OA\Property(type="array")
     * @var ArrayCollection
     */
    #[ORM\OneToMany(mappedBy: 'customer', targetEntity: User::class)]
    private $users;

    /**
     * @OA\Property(type="string")
     * @var string|null
     */
    #[ORM\Column(type: 'string', length: 255)]
    private ?string $password;

    #[Pure] public function __construct()
    {
        $this->roles = ['ROLE_USER'];
        $this->users = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

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

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        return $this->roles;
    }

    public function setRoles(array $role): self
    {
        $this->roles = $role;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->setCustomer($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getCustomer() === $this) {
                $user->setCustomer(null);
            }
        }

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return $this->email;
    }


}
