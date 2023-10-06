<?php
declare(strict_types=1);

namespace Atompulse\Logger\Processor;

use Atompulse\Logger\LogEntry;
use Atompulse\Logger\LogLevel;

interface LogProcessorInterface
{
    public function log(LogEntry $log): void;

    public function setLogLevel(LogLevel $level): void;

    public function getLogLevel(): LogLevel;
}
