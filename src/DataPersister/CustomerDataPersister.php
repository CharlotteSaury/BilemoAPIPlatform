<?php

namespace App\DataPersister;

use App\Entity\Customer;
use App\Repository\CustomerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;
use App\Exception\ResourceValidationException;
use ApiPlatform\Core\DataPersister\DataPersisterInterface;

class CustomerDataPersister implements DataPersisterInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var Security
     */
    private $security;

    /**
     * @var CustomerRepository
     */
    private $customerRepository;
    
    public function __construct(EntityManagerInterface $entityManager, Security $security, CustomerRepository $customerRepository)
    {
        $this->entityManager = $entityManager;
        $this->security = $security;
        $this->customerRepository = $customerRepository;
    }

    /**
     * @param Customer $data
     * @return boolean
     */
    public function supports($data): bool
    {
        return $data instanceof Customer;
    }

    public function persist($data)
    {
        $currentClient = $this->security->getUser();
        $existingCustomer = $this->customerRepository->findOneBy(['email' => $data->getEmail()]);
        if (!$existingCustomer) {
            $data->addClient($currentClient);
            $this->entityManager->persist($data);
            $this->entityManager->flush();
        } elseif ($existingCustomer->getClients()->contains($currentClient)) {
            throw new ResourceValidationException('This customer is already associated to this client');
        } else {
            $existingCustomer->addClient($currentClient);
            $this->entityManager->flush();
            return $existingCustomer;
        }
    }

    public function remove($data)
    {
        $this->entityManager->remove($data);
        $this->entityManager->flush();
    }
}