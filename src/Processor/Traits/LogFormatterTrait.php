<?php
declare(strict_types=1);

namespace Atompulse\Logger\Processor\Traits;

use Atompulse\Logger\LogEntry;
use DateTimeImmutable;

trait LogFormatterTrait
{
    private function formatLog(LogEntry $logEntry): string
    {
        $context = json_encode($logEntry->context);

        return "[{$logEntry->time->format(DateTimeImmutable::W3C)}] [{$logEntry->level->name}] $logEntry->message [$context]";
    }
}
