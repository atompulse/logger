<?php
declare(strict_types=1);

namespace Atompulse\Logger;

use Atompulse\Logger\Processor\LogProcessorInterface;
use DateTimeImmutable;

interface LoggerInterface
{
    public function setLogLevel(LogLevel $level): void;

    public function setTargetLogLevel(string $target, LogLevel $level): void;

    public function addProcessor(string $target, LogProcessorInterface $processor): void;

    public function log(
        string $message,
        LogLevel $level,
        array $context = [],
        DateTimeImmutable $time = null
    ): void;
}
