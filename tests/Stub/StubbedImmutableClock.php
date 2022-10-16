<?php

declare(strict_types=1);

namespace Tests\ECommerce\Stub;

use DateTimeImmutable;
use DateTimeZone;
use ECommerce\Domain\SPI\ImmutableClock;

final class StubbedImmutableClock implements ImmutableClock
{
    public function now(): DateTimeImmutable
    {
        return new DateTimeImmutable('1995-05-01 14:05:00', new DateTimeZone('UTC'));
    }
}
