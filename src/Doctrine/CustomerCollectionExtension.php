<?php

namespace App\Doctrine;

use App\Entity\Customer;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Security\Core\Security;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\QueryCollectionExtensionInterface;

class CustomerCollectionExtension implements QueryCollectionExtensionInterface
{
    /**
     * @var Security
     */
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function applyToCollection(QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, string $operationName = null)
    {
        if ($resourceClass !== Customer::class) {
            return;
        }
        $client = $this->security->getUser();
        $rootAlias = $queryBuilder->getRootAliases()[0];
        $queryBuilder  
            ->leftJoin($rootAlias.'.clients', 'clients')
            ->andWhere('clients = :val')
            ->setParameter('val', $client);
    }
}