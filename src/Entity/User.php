<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\GraphQl\Mutation;
use ApiPlatform\Metadata\GraphQl\Query;
use ApiPlatform\Metadata\GraphQl\QueryCollection;
use ApiPlatform\Metadata\Post;
use App\Repository\UserRepository;
use App\Resolver\MeResolver;
use App\State\UserMailProcessor;
use App\State\UserPasswordHasher;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[ApiResource(graphQlOperations: [
    new Mutation(name: 'create', processor: UserMailProcessor::class, args: [
        'email' => ['type' => 'String!'],
        'password' => ['type' => 'String!'],
        'roles' => ['type' => '[String]'],
        'verificationCode' => ['type' => 'Int'],
        'name' => ['type' => 'String!']
    ]),
    new Query(
        name: 'item_query'
    ),
    new Query(
        name: 'me',
        security: 'is_granted("ROLE_USER")',
        args: [
            'id' => ['type' => 'ID'],
        ],
        resolver: MeResolver::class)
])]
#[ApiFilter(SearchFilter::class, properties: ['name' => 'partial'])]
#[Post(processor: UserMailProcessor::class)]
#[GetCollection]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['friends:collection'])]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    private ?string $email = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column(nullable: true)]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    /**
     * @var Collection<int, Project>
     */
    #[ORM\OneToMany(targetEntity: Project::class, mappedBy: 'owner')]
    private Collection $projects;

    /**
     * @var Collection<int, TeamItem>
     */
    #[ORM\OneToMany(targetEntity: TeamItem::class, mappedBy: 'user', orphanRemoval: true)]
    private Collection $teamItems;

    #[ORM\Column(nullable: true)]
    private ?int $verification_code = null;

    #[ORM\Column]
    private ?bool $is_verified = false;

    /**
     * @var Collection<int, Todo>
     */
    #[ORM\OneToMany(targetEntity: Todo::class, mappedBy: 'assigned')]
    private Collection $todos;

    /**
     * @var Collection<int, Friend>
     */
    #[ORM\OneToMany(targetEntity: Friend::class, mappedBy: 'applicant', orphanRemoval: true)]
    private Collection $friends;

    #[ORM\Column(length: 255)]
    #[Groups(['friends:collection', 'teamItem:collection'])]
    private ?string $name = null;

    public function __construct()
    {
        $this->projects = new ArrayCollection();
        $this->teamItems = new ArrayCollection();
        $this->roles = ['ROLE_USER'];
        $this->is_verified = false;
        $this->todos = new ArrayCollection();
        $this->friends = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     *
     * @return list<string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }



    public function setPassword(string $password): static
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
     * @return Collection<int, Project>
     */
    public function getProjects(): Collection
    {
        return $this->projects;
    }

    public function addProject(Project $project): static
    {
        if (!$this->projects->contains($project)) {
            $this->projects->add($project);
            $project->setOwner($this);
        }

        return $this;
    }

    public function removeProject(Project $project): static
    {
        if ($this->projects->removeElement($project)) {
            // set the owning side to null (unless already changed)
            if ($project->getOwner() === $this) {
                $project->setOwner(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, TeamItem>
     */
    public function getTeamItems(): Collection
    {
        return $this->teamItems;
    }

    public function addTeamItem(TeamItem $teamItem): static
    {
        if (!$this->teamItems->contains($teamItem)) {
            $this->teamItems->add($teamItem);
            $teamItem->setUser($this);
        }

        return $this;
    }

    public function removeTeamItem(TeamItem $teamItem): static
    {
        if ($this->teamItems->removeElement($teamItem)) {
            // set the owning side to null (unless already changed)
            if ($teamItem->getUser() === $this) {
                $teamItem->setUser(null);
            }
        }

        return $this;
    }

    public function getVerificationCode(): ?int
    {
        return $this->verification_code;
    }

    public function setVerificationCode(int $verification_code): static
    {
        $this->verification_code = $verification_code;

        return $this;
    }

    public function isVerified(): ?bool
    {
        return $this->is_verified;
    }

    public function setVerified(bool $is_verified): static
    {
        $this->is_verified = $is_verified;

        return $this;
    }

    /**
     * @return Collection<int, Todo>
     */
    public function getTodos(): Collection
    {
        return $this->todos;
    }

    public function addTodo(Todo $todo): static
    {
        if (!$this->todos->contains($todo)) {
            $this->todos->add($todo);
            $todo->setAssigned($this);
        }

        return $this;
    }

    public function removeTodo(Todo $todo): static
    {
        if ($this->todos->removeElement($todo)) {
            // set the owning side to null (unless already changed)
            if ($todo->getAssigned() === $this) {
                $todo->setAssigned(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Friend>
     */
    public function getFriends(): Collection
    {
        return $this->friends;
    }

    public function addFriend(Friend $friend): static
    {
        if (!$this->friends->contains($friend)) {
            $this->friends->add($friend);
            $friend->setApplicant($this);
        }

        return $this;
    }

    public function removeFriend(Friend $friend): static
    {
        if ($this->friends->removeElement($friend)) {
            // set the owning side to null (unless already changed)
            if ($friend->getApplicant() === $this) {
                $friend->setApplicant(null);
            }
        }

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }
}
