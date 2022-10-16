<?php

declare(strict_types=1);

namespace Tests\ECommerce\Fake;

use ECommerce\Domain\Model\Customer;
use ECommerce\Domain\Model\CustomerRepository;
use OutOfBoundsException;

final class FakeCustomerRepository implements CustomerRepository
{
    private array $customers = [];

    public function get(string $id): Customer
    {
        if (false === \array_key_exists($id, $this->customers)) {
            throw new OutOfBoundsException("Customer with identifier $id not found.");
        }

        return $this->customers[$id];
    }

    public function add(Customer $customer): void
    {
        $this->customers[$customer->id] = $customer;
    }

    public function remove(string $id): void
    {
        $this->get($id);

        unset($this->customers[$id]);
    }

    public function generateIdentifier(): string
    {
        return (string) (count($this->customers) + 1);
    }
}
