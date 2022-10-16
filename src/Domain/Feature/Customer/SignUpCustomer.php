<?php

declare(strict_types=1);

namespace ECommerce\Domain\Feature\Customer;

final class SignUpCustomer
{
    public function __construct(
        public readonly string  $name,
        public readonly string  $email,
        public readonly string  $addressName,
        public readonly string  $street1,
        public readonly ?string $street2,
        public readonly string  $zipcode,
        public readonly string  $countryCode,
    ) {
    }
}
