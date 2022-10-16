<?php

declare(strict_types=1);

namespace ECommerce\Infrastructure\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\Persistence\ManagerRegistry;
use ECommerce\Domain\Model\Customer;
use ECommerce\Domain\Model\CustomerRepository as Repository;
use Ramsey\Uuid\UuidFactoryInterface;

final class CustomerRepository extends ServiceEntityRepository implements Repository
{
    public function __construct(ManagerRegistry $registry, private readonly UuidFactoryInterface $uuidFactory)
    {
        parent::__construct($registry, Customer::class);
    }

    public function get(string $id): Customer
    {
        $customer = $this->find($id);

        if (null === $customer) {
            throw EntityNotFoundException::fromClassNameAndIdentifier(Customer::class, $id);
        }

        return $customer;
    }

    public function add(Customer $customer): void
    {
        $this->_em->persist($customer->address());
        $this->_em->persist($customer);
        $this->_em->flush();
    }

    public function remove(string $id): void
    {
        $customer = $this->get($id);

        $this->_em->remove($customer->address());
        $this->_em->remove($customer);
        $this->_em->flush();
    }

    public function generateIdentifier(): string
    {
        return $this->uuidFactory->uuid4()->toString();
    }
}
