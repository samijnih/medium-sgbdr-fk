<?php

declare(strict_types=1);

namespace ECommerce\Domain\Model;

use DateTimeImmutable;
use ECommerce\Domain\SPI\ImmutableClock;

final class Customer
{
    public readonly DateTimeImmutable $createdAt;
    public ?DateTimeImmutable $updatedAt;
    private Address $address;

    public function __construct(
        public readonly string $id,
        public string $name,
        public string $email,
        AddressStruct $address,
        ImmutableClock $immutableClock,
    ) {
        $this->address = new Address(
            customer: $this,
            id: $address->id,
            name: $address->name,
            street1: $address->street1,
            street2: $address->street2,
            zipcode: $address->zipcode,
            countryCode: $address->countryCode,
            immutableClock: $immutableClock,
        );
        $this->createdAt = $immutableClock->now();
        $this->updatedAt = null;
    }

    public function address(): Address
    {
        return $this->address;
    }
}
