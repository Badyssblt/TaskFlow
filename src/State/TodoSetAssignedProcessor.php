<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\Project;
use App\Entity\Todo;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\DependencyInjection\Attribute\AsDecorator;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

final readonly class TodoSetAssignedProcessor implements ProcessorInterface
{
    public function __construct(
        #[Autowire(service: 'api_platform.doctrine.orm.state.persist_processor')]
        private ProcessorInterface $processor,
        private Security $security,
    ) {}

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): Todo
    {
        if ($data instanceof Todo) {
            $project = $data->getProject();
            $user = $this->security->getUser();
            $team = $project->getTeamItems();

            $isInTeam = false;

            if ($user === $project->getOwner()) {
                $isInTeam = true;
            } else {
                foreach ($team as $teamItem) {
                    if ($teamItem->getUser() === $user) {
                        $isInTeam = true;
                        break;
                    }
                }
            }

            if (!$isInTeam) {
                throw new AccessDeniedHttpException('You must be a member of the project team to create or assign this todo.');
            }

            if ($data->getId() === null) {
                $data->setAssigned($user);
            } else {
                if ($user !== $project->getOwner()) {
                    throw new AccessDeniedHttpException('You do not have permission to assign todos for this project.');
                }
                $data->setAssigned($user);
            }
        }

        return $this->processor->process($data, $operation, $uriVariables, $context);
    }
}
