<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\User;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class UserPatchProcessor implements ProcessorInterface
{
    public function __construct(
        #[Autowire(service: 'api_platform.doctrine.orm.state.persist_processor')]
        private ProcessorInterface $processor,
        private UserPasswordHasherInterface $passwordHasher,
    )
    {

    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): User|JsonResponse
    {
        if ($data instanceof User) {
            $data->setName($data->getName());
            $data->setEmail($data->getEmail());
            $data->setPassword($this->passwordHasher->hashPassword($data, $data->getPassword()));
        }


        return $this->processor->process($data, $operation, $uriVariables, $context);
    }
}
