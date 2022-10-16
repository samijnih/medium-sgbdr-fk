<?php

declare(strict_types=1);

namespace ECommerce\Domain\Feature\Customer;

use ECommerce\Domain\Model\CustomerRepository;

final class RemoveCustomer
{
    public function __construct(
        private readonly CustomerRepository $customerRepository,
    ) {
    }

    public function __invoke(string $customerId): void
    {
        $this->customerRepository->remove($customerId);
    }
}
