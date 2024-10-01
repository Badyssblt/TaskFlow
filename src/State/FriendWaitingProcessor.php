<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Repository\FriendRepository;
use Symfony\Bundle\SecurityBundle\Security;

class FriendWaitingProcessor implements ProviderInterface
{
    private $friendRepository;
    private $security;

    public function __construct(FriendRepository $friendRepository, Security $security)
    {
        $this->friendRepository = $friendRepository;
        $this->security = $security;
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        $user = $this->security->getUser();

        if (!$user) {
            throw new \LogicException('User not found');
        }

        $state = $context['filters']['state'] ?? null;


        return $this->friendRepository->findBy(['receiver' => $user, 'state' => $state]);
    }
}
