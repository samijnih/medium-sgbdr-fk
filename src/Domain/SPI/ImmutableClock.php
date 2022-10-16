<?php

declare(strict_types=1);

namespace ECommerce\Domain\SPI;

use DateTimeImmutable;

interface ImmutableClock
{
    public function now(): DateTimeImmutable;
}
