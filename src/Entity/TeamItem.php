<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\GraphQl\DeleteMutation;
use ApiPlatform\Metadata\GraphQl\Mutation;
use App\Repository\TeamItemRepository;
use App\State\FriendSetApplicantProcessor;
use App\State\TeamItemDeleteProcessor;
use App\State\TeamItemProvider;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: TeamItemRepository::class)]
#[ApiResource(
    graphQlOperations: [
        new Mutation(name: 'create',  security: "is_granted('ROLE_USER')", args: [
            'user' => ['type' => 'String!'],
            'project' => ['type' => 'String!'],
            'state' => ['type' => 'String!'],
            'createdAt' => ['type' => 'String!'],
        ]),
        new Mutation(name: 'update',  security: "is_granted('ROLE_USER')"),
        new DeleteMutation(name: 'delete',  security: "is_granted('ROLE_USER')", processor: TeamItemDeleteProcessor::class),
    ]
)]
#[GetCollection(
    provider: TeamItemProvider::class,
    security: "is_granted('ROLE_USER')",
    normalizationContext: ['groups' => ['teamItem:collection']]
)]
#[UniqueEntity(fields:  ['user', 'project'], message: 'Une invitation a déjà été envoyé')]
class TeamItem
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['teamItem:collection'])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'teamItems')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'teamItems')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['teamItem:collection'])]
    private ?Project $project = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column(length: 255)]
    private ?string $state = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getProject(): ?Project
    {
        return $this->project;
    }

    public function setProject(?Project $project): static
    {
        $this->project = $project;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getState(): ?string
    {
        return $this->state;
    }

    public function setState(string $state): static
    {
        $this->state = $state;

        return $this;
    }
}
