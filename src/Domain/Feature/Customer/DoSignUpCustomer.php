<?php

declare(strict_types=1);

namespace ECommerce\Domain\Feature\Customer;

use Assert\Assert;
use ECommerce\Domain\Model\AddressStruct;
use ECommerce\Domain\Model\Customer;
use ECommerce\Domain\Model\CustomerRepository;
use ECommerce\Domain\SPI\ImmutableClock;

final class DoSignUpCustomer
{
    public function __construct(
        private readonly CustomerRepository $customerRepository,
        private readonly ImmutableClock $immutableClock,
    ) {
    }

    public function __invoke(SignUpCustomer $command): void
    {
        $this->validateCommand($command);

        $customer = new Customer(
            $this->customerRepository->generateIdentifier(),
            $command->name,
            $command->email,
            new AddressStruct(
                $this->customerRepository->generateIdentifier(),
                $command->addressName,
                $command->street1,
                $command->street2,
                $command->zipcode,
                $command->countryCode
            ),
            $this->immutableClock,
        );

        $this->customerRepository->add($customer);
    }

    private function validateCommand(SignUpCustomer $command): void
    {
        Assert::lazy()
            ->that($command->name)->notEmpty()->maxLength(255)
            ->that($command->email)->maxLength(255)->email()
            ->that($command->addressName)->notEmpty()->maxLength(255)
            ->that($command->street1)->notEmpty()->maxLength(255)
            ->that($command->street2)->nullOr()->notBlank()->maxLength(255)
            ->that($command->zipcode)->length(5)
            ->that($command->countryCode)->length(2)
            ->verifyNow();
    }
}
