<?php
namespace App\State;

use App\Entity\Group;
use App\Repository\GroupRepository;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;

/**
 * @implements ProviderInterface<iterable|Group|null>
 */
final class GroupReportProvider implements ProviderInterface
{
    public function __construct(
        private GroupRepository $groupRepository,
    ) {
        
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): iterable|Group|null
    {
        if ($operation instanceof CollectionOperationInterface) {
            return $this->groupRepository->findAll();
        }

        return $this->groupRepository->findById($uriVariables['id']) ?? null;
    }
}