<?php
declare(strict_types=1);

namespace Atompulse\Logger\Processor\Traits;

use Atompulse\Logger\LogLevel;

trait SharedLogLevelProceduresTrait
{
    private LogLevel $logLevel;

    public function setLogLevel(LogLevel $level): void
    {
        $this->logLevel = $level;
    }

    public function getLogLevel(): LogLevel
    {
        return $this->logLevel;
    }
}
