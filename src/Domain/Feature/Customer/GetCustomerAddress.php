<?php

declare(strict_types=1);

namespace ECommerce\Domain\Feature\Customer;

use ECommerce\Domain\Model\AddressStruct;
use ECommerce\Domain\Model\CustomerRepository;

final class GetCustomerAddress
{
    public function __construct(
        private readonly CustomerRepository $customerRepository,
    ) {
    }

    public function __invoke(string $customerId): AddressStruct
    {
        $customer = $this->customerRepository->get($customerId);

        return AddressStruct::fromEntity($customer->address());
    }
}
