<?php
declare(strict_types=1);

namespace Atompulse\Logger;

use Atompulse\Logger\Processor\LogProcessorInterface;
use DateTimeImmutable;
use RuntimeException;

final class Logger implements LoggerInterface
{
    private array $processors = [];

    public function __construct(
        private LogLevel $logLevel = LogLevel::Debug
    ) {
    }

    public function setLogLevel(LogLevel $level): void
    {
        $this->logLevel = $level;
    }

    public function setTargetLogLevel(string $target, LogLevel $level): void
    {
        if (!array_key_exists($target, $this->processors)) {
            throw new RuntimeException("Logger target [$target] not defined");
        }

        $this->processors[$target]->setLogLevel($level);
    }

    public function addProcessor(string $target, LogProcessorInterface $processor): void
    {
        $this->processors[$target] = $processor;
    }

    public function log(
        string $message,
        LogLevel $level,
        array $context = [],
        DateTimeImmutable $time = null
    ): void {

        if (!$this->shouldLogByDefault($level)) {
            return;
        }

        foreach ($this->processors as $processor) {
            if ($this->shouldLogForChannel($processor, $level)) {
                $processor->log(
                    new LogEntry(
                        $message,
                        $level,
                        $time ?? new DateTimeImmutable('now'),
                        $context
                    )
                );
            }
        }
    }

    private function shouldLogByDefault(LogLevel $level): bool
    {
        return $level->value >= $this->logLevel->value;
    }

    private function shouldLogForChannel(LogProcessorInterface $channel, LogLevel $level): bool
    {
        return $level->value >= $channel->getLogLevel()->value;
    }
}
