<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Repository\TeamItemRepository;
use Symfony\Bundle\SecurityBundle\Security;

class TeamItemProvider implements ProviderInterface
{
    private $teamItemRepository;
    private $security;

    public function __construct(TeamItemRepository $teamItemRepository, Security $security)
    {
        $this->teamItemRepository = $teamItemRepository;
        $this->security = $security;
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        $user = $this->security->getUser();

        if (!$user) {
            throw new \LogicException('User not found');
        }


        $teamItems = $this->teamItemRepository->findBy(['user' => $user, 'state' => 'waiting']);

        return $teamItems;
    }
}
