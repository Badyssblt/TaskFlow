<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GraphQl\DeleteMutation;
use ApiPlatform\Metadata\GraphQl\Mutation;
use ApiPlatform\Metadata\GraphQl\Query;
use ApiPlatform\Metadata\GraphQl\QueryCollection;
use App\Enum\State;
use App\Repository\ProjectRepository;
use App\State\ProjectSetOwnerProcessor;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: ProjectRepository::class)]
#[ApiResource(graphQlOperations: [
    new Mutation(name: 'create', processor: ProjectSetOwnerProcessor::class),
    new Mutation(name: 'update', security: "object.owner === user"),
    new DeleteMutation(name: 'delete', security: "object.owner === user"),
    new QueryCollection(),
    new Query(),

])]
class Project
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['teamItem:collection'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column]
    private ?int $priority = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $started_at = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $end_at = null;

    #[ORM\ManyToOne(inversedBy: 'projects')]
    #[ORM\JoinColumn(nullable: true)]
    #[Groups(['teamItem:collection'])]
    public ?User $owner = null;

    #[ORM\Column(type: Types::STRING, enumType: State::class)]
    private ?State $state = null;


    /**
     * @var Collection<int, TeamItem>
     */
    #[ORM\OneToMany(targetEntity: TeamItem::class, mappedBy: 'project', orphanRemoval: true)]
    private Collection $teamItems;

    #[ORM\Column]
    private ?float $budget = null;

    #[ORM\Column(length: 255)]
    private ?string $visibility = null;

    /**
     * @var Collection<int, Todo>
     */
    #[ORM\OneToMany(targetEntity: Todo::class, mappedBy: 'project', orphanRemoval: true)]
    private Collection $todos;

    public function __construct()
    {
        $this->teamItems = new ArrayCollection();
        $this->todos = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getPriority(): ?int
    {
        return $this->priority;
    }

    public function setPriority(int $priority): static
    {
        $this->priority = $priority;

        return $this;
    }

    public function getStartedAt(): ?\DateTimeImmutable
    {
        return $this->started_at;
    }

    public function setStartedAt(\DateTimeImmutable $started_at): static
    {
        $this->started_at = $started_at;

        return $this;
    }

    public function getEndAt(): ?\DateTimeImmutable
    {
        return $this->end_at;
    }

    public function setEndAt(\DateTimeImmutable $end_at): static
    {
        $this->end_at = $end_at;

        return $this;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): static
    {
        $this->owner = $owner;

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
            $teamItem->setProject($this);
        }

        return $this;
    }

    public function removeTeamItem(TeamItem $teamItem): static
    {
        if ($this->teamItems->removeElement($teamItem)) {
            // set the owning side to null (unless already changed)
            if ($teamItem->getProject() === $this) {
                $teamItem->setProject(null);
            }
        }

        return $this;
    }

    public function getBudget(): ?float
    {
        return $this->budget;
    }

    public function setBudget(float $budget): static
    {
        $this->budget = $budget;

        return $this;
    }

    public function getVisibility(): ?string
    {
        return $this->visibility;
    }

    public function setVisibility(string $visibility): static
    {
        $this->visibility = $visibility;

        return $this;
    }

    public function getState(): ?State
    {
        return $this->state;
    }

    public function setState(?State $state): void
    {
        $this->state = $state;
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
            $todo->setProject($this);
        }

        return $this;
    }

    public function removeTodo(Todo $todo): static
    {
        if ($this->todos->removeElement($todo)) {
            // set the owning side to null (unless already changed)
            if ($todo->getProject() === $this) {
                $todo->setProject(null);
            }
        }

        return $this;
    }

}
