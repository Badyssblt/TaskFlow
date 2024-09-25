<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\User;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class UserMailProcessor implements ProcessorInterface
{
    public function __construct(
        #[Autowire(service: 'api_platform.doctrine.orm.state.persist_processor')]
        private ProcessorInterface $processor,
        private httpClientInterface $client,
        private UserPasswordHasherInterface $passwordHasher,
    )
    {

    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): User|JsonResponse
    {
        if (!$data->getPassword()) {
            return $this->processor->process($data, $operation, $uriVariables, $context);
        }


        $hashedPassword = $this->passwordHasher->hashPassword(
            $data,
            $data->getPassword()
        );

        $code = random_int(10000, 99999);


        $data->setPassword($hashedPassword);
        $data->setVerificationCode($code);

        try {
            $this->client->request(
                'POST',
                "https://automatic.badyssblilita.fr/v1/api/send-mail/2253409f-b129-40f1-a575-e41cb6ff844c?apiKey=test",
                [
                    "json" => [
                        "data" => [
                            "verificationCode" => $code,
                        ],
                        "receiver" => $data->getEmail(),
                    ]
                ]
            );
        }catch (\Exception $exception){
            return new JsonResponse([
                'message' => 'An error occurred: ' . $exception->getMessage(),
            ], 500);
        }


        return $this->processor->process($data, $operation, $uriVariables, $context);
    }
}
