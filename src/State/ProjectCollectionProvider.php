<?php

namespace App\State;

use ApiPlatform\Doctrine\Orm\Paginator as DoctrineOrmPaginator;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Repository\ProjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Doctrine\ORM\Tools\Pagination\Paginator as DoctrinePaginator;

class ProjectCollectionProvider implements ProviderInterface
{
    private ProjectRepository $projectRepository;
    private Security $security;
    private EntityManagerInterface $entityManager;

    public function __construct(ProjectRepository $projectRepository, Security $security, EntityManagerInterface $entityManager)
    {
        $this->projectRepository = $projectRepository;
        $this->security = $security;
        $this->entityManager = $entityManager;
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        $user = $this->security->getUser();

        if (!$user) {
            throw new \Exception('User not authenticated');
        }

        $page = $context['pagination']['page'] ?? 1;
        $itemsPerPage = $context['pagination']['itemsPerPage'] ?? 10;

        $queryBuilder = $this->projectRepository->createQueryBuilder('p')
            ->leftJoin('p.teamItems', 'ti')
            ->andWhere('p.owner = :user OR (ti.user = :user AND ti.state = :acceptedState)')
            ->setParameter('user', $user)
            ->setParameter('acceptedState', 'accepted')
            ->groupBy('p.id');

        $doctrinePaginator = new DoctrinePaginator($queryBuilder);

        $query = $doctrinePaginator->getQuery();
        $query->setFirstResult(($page - 1) * $itemsPerPage);
        $query->setMaxResults($itemsPerPage);

        return new DoctrineOrmPaginator($doctrinePaginator);
    }
}
