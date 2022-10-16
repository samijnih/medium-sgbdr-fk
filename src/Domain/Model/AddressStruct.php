<?php

declare(strict_types=1);

namespace ECommerce\Domain\Model;

final class AddressStruct
{
    public function __construct(
        public readonly string $id,
        public readonly string $name,
        public readonly string $street1,
        public readonly ?string $street2,
        public readonly string $zipcode,
        public readonly string $countryCode,
    ) {
    }

    public static function fromEntity(Address $address): self
    {
        return new self(
            id: $address->id(),
            name: $address->name(),
            street1: $address->street1(),
            street2: $address->street2(),
            zipcode: $address->zipcode(),
            countryCode: $address->countryCode(),
        );
    }
}
