<?php
declare(strict_types=1);

namespace Atompulse\Logger;

use DateTimeImmutable;

final readonly class LogEntry
{
    public function __construct(
        public string $message,
        public LogLevel $level,
        public DateTimeImmutable $time,
        public array $context = []
    ) {
    }
}
