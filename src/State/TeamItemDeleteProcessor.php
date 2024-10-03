<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\TeamItem;
use App\Repository\TeamItemRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Doctrine\ORM\EntityManagerInterface;

class TeamItemDeleteProcessor implements ProcessorInterface
{
    private Security $security;
    private ProcessorInterface $processor;
    private EntityManagerInterface $entityManager;

    public function __construct(Security $security,
                                EntityManagerInterface $entityManager,
                                #[Autowire(service: 'api_platform.doctrine.orm.state.remove_processor')]
                                ProcessorInterface $processor)
    {
        $this->security = $security;
        $this->entityManager = $entityManager;
        $this->processor = $processor;
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): void
    {
        if (!$data instanceof TeamItem) {
            throw new \RuntimeException('Invalid data');
        }

        $currentUser = $this->security->getUser();

        $project = $data->getProject();

        if ($project->getOwner() !== $currentUser) {
            throw new AccessDeniedHttpException('Seul le propriétaire du projet peut supprimer cet élément.');
        }

        $this->processor->process($data, $operation, $uriVariables, $context);
    }
}
