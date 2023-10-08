<?php
declare(strict_types=1);

namespace Atompulse\Logger\Processor;

use Atompulse\Logger\Processor\Traits\LogFormatterTrait;
use Atompulse\Logger\Processor\Traits\SharedLogLevelProceduresTrait;
use Atompulse\Logger\LogEntry;
use Atompulse\Logger\LogLevel;

class ConsoleProcessor implements LogProcessorInterface
{
    use SharedLogLevelProceduresTrait;
    use LogFormatterTrait;

    public function __construct(
        private LogLevel $logLevel
    ) {
    }

    public function log(LogEntry $log): void
    {
        if ($log->level->value >= $this->logLevel->value) {
            echo $this->formatLog($log) . PHP_EOL;
        }
    }
}
