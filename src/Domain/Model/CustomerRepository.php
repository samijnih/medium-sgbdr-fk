<?php

declare(strict_types=1);

namespace ECommerce\Domain\Model;

interface CustomerRepository
{
    public function get(string $id): Customer;

    public function add(Customer $customer): void;

    public function remove(string $id): void;

    public function generateIdentifier(): string;
}
