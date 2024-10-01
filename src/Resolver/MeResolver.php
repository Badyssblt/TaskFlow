<?php

namespace App\Resolver;

use ApiPlatform\GraphQl\Resolver\QueryItemResolverInterface;
use App\Repository\UserRepository;
use Symfony\Bundle\SecurityBundle\Security;

class MeResolver implements QueryItemResolverInterface
{
    private $security;
    private $userRepository;

    public function __construct(Security $security, UserRepository $userRepository)
    {
        $this->security = $security;
        $this->userRepository = $userRepository;
    }

    public function __invoke($item, array $context): object
    {
        // Récupérer l'utilisateur connecté
        $user = $this->security->getUser();

        // Retourner l'utilisateur connecté
        if ($user) {
            return $user;
        }

        // Si aucun utilisateur n'est connecté, retourner null ou lever une exception
        throw new \LogicException('User is not authenticated.');
    }
}