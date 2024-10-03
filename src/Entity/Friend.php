<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\GraphQl\Mutation;
use ApiPlatform\Metadata\Patch;
use App\Repository\FriendRepository;
use App\State\FriendProcessor;
use App\State\FriendSetApplicantProcessor;
use App\State\FriendWaitingProcessor;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: FriendRepository::class)]
#[UniqueEntity(
    fields: ['applicant', 'receiver'],
    message: 'Une demande est déjà en cours'
)]
#[ApiResource(graphQlOperations: [
    new Mutation(name: 'create',  processor: FriendSetApplicantProcessor::class, security: "is_granted('ROLE_USER')", args: [
        'receiver' => ['type' => 'String!'],
        'applicant' => ['type' => 'String'],
        'state' => ['type' => 'String!'],
    ])

])]
#[GetCollection(
    security: "is_granted('ROLE_USER')",
    provider: FriendWaitingProcessor::class,
    securityMessage: "Vous devez être connecté pour accéder à cette ressource",
    normalizationContext: ['groups' => ['friends:collection']]
)]
#[GetCollection(
    uriTemplate: '/myfriends',
    provider: FriendProcessor::class,
    normalizationContext: ['groups' => ['friends:collection']]
)]
#[Patch(
    security: "is_granted('ROLE_USER') and object.receiver == user",
    normalizationContext: ['groups' => ['friends:collection']]
)]

class Friend
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['friends:collection'])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'friends')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['friends:collection'])]
    public ?User $applicant = null;

    #[ORM\ManyToOne(inversedBy: 'friends')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['friends:collection'])]
    public ?User $receiver = null;

    #[ORM\Column(length: 255)]
    #[Groups(['friends:collection'])]
    private ?string $state = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getApplicant(): ?User
    {
        return $this->applicant;
    }

    public function setApplicant(?User $applicant): static
    {
        $this->applicant = $applicant;

        return $this;
    }

    public function getReceiver(): ?User
    {
        return $this->receiver;
    }

    public function setReceiver(?User $receiver): static
    {
        $this->receiver = $receiver;

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
