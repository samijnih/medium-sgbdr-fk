<?php

declare(strict_types=1);

namespace ECommerce\Domain\Model;

use DateTimeImmutable;
use ECommerce\Domain\SPI\ImmutableClock;

final class Address
{
    private readonly DateTimeImmutable $createdAt;
    private ?DateTimeImmutable $updatedAt;

    public function __construct(
        private readonly Customer $customer,
        private readonly string $id,
        private string $name,
        private string $street1,
        private ?string $street2,
        private string $zipcode,
        private string $countryCode,
        ImmutableClock $immutableClock,
    ) {
        $this->createdAt = $immutableClock->now();
        $this->updatedAt = null;
    }

    public function id(): string
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function street1(): string
    {
        return $this->street1;
    }

    public function street2(): ?string
    {
        return $this->street2;
    }

    public function zipcode(): string
    {
        return $this->zipcode;
    }

    public function countryCode(): string
    {
        return $this->countryCode;
    }
}
