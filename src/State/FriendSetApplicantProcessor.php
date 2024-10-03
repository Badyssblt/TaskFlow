<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\Friend;
use App\Repository\FriendRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class FriendSetApplicantProcessor implements ProcessorInterface
{
    private FriendRepository $friendRepository;
    private Security $security;
    private ProcessorInterface $processor;

    public function __construct(FriendRepository $friendRepository, Security $security,
                                #[Autowire(service: 'api_platform.doctrine.orm.state.persist_processor')]

                                ProcessorInterface $processor)
    {
        $this->friendRepository = $friendRepository;
        $this->security = $security;
        $this->processor = $processor;
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): Friend
    {
        // Vérifiez que l'instance est bien de type Friend
        if ($data instanceof Friend) {
            $currentUser = $this->security->getUser();
            $data->setApplicant($currentUser);

            // Vérifiez si une relation d'amitié existe déjà
            $existingFriend = $this->friendRepository->findOneBy([
                'applicant' => $currentUser,
                'receiver' => $data->getReceiver()
            ]);

            if ($existingFriend) {
                throw new BadRequestHttpException('Une demande d\'amitié est déjà en cours avec cet utilisateur.');
            }
        }

        // Si tout va bien, passez au traitement normal
        return $this->processor->process($data, $operation, $uriVariables, $context);
    }
}

