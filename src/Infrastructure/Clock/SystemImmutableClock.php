<?php

declare(strict_types=1);

namespace ECommerce\Infrastructure\Clock;

use DateTimeImmutable;
use DateTimeZone;
use ECommerce\Domain\SPI\ImmutableClock;

final class SystemImmutableClock implements ImmutableClock
{
    public function now(): DateTimeImmutable
    {
        return new DateTimeImmutable('now', new DateTimeZone('UTC'));
    }
}
