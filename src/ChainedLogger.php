<?php
declare(strict_types=1);

namespace Atompulse\Logger;

use Atompulse\Logger\Processor\ChainableProcessorInterface;
use Atompulse\Logger\Processor\LogProcessorInterface;
use DateTimeImmutable;
use RuntimeException;

final class ChainedLogger implements LoggerInterface
{
    /**
     * @var ChainableProcessorInterface[]
     */
    private array $processors = [];

    private ?ChainableProcessorInterface $firstProcessor = null;

    private ?ChainableProcessorInterface $lastProcessor = null;

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
        if (!isset($this->processors[$target])) {
            throw new RuntimeException("Logger target [$target] not defined");
        }

        $this->processors[$target]->setLogLevel($level);
    }

    public function addProcessor(string $target, LogProcessorInterface $processor): void
    {
        if (!$processor instanceof ChainableProcessorInterface) {
            throw new RuntimeException("This logger only supports ChainableProcessorInterface processors");
        }

        if ($this->lastProcessor) {
            $this->lastProcessor->setNext($processor);
        } else {
            $this->firstProcessor = $processor;
        }
        $this->lastProcessor = $processor;


        $this->processors[$target] = $processor;
    }

    public function log(
        string $message,
        LogLevel $level,
        array $context = [],
        DateTimeImmutable $time = null
    ): void {

        if (!$this->shouldLogByDefault($level) || $this->firstProcessor === null) {
            return;
        }

        $logEntry = new LogEntry(
            $message,
            $level,
            $time ?? new DateTimeImmutable('now'),
            $context
        );

        $this->firstProcessor->log($logEntry);
    }

    public function removeProcessor(string $target): void
    {
        if (!isset($this->processors[$target])) {
            throw new RuntimeException("Logger target [$target] not defined");
        }

        $keys = array_keys($this->processors);
        $index = array_search($target, $keys);

        // not the first processor and not the last, rewire the chain
        if ($index > 0 && $index < count($keys) - 1) {
            $previousProcessor = $this->processors[$keys[$index - 1]];
            $nextProcessor = $this->processors[$keys[$index + 1]];
            $previousProcessor->setNext($nextProcessor);
        } elseif ($index == 0) { // the first processor
            $this->firstProcessor = isset($keys[1]) ? $this->processors[$keys[1]] : null;
        } elseif ($index == count($keys) - 1) { // the last processor
            $this->lastProcessor = $this->processors[$keys[$index - 1]];
        }

        unset($this->processors[$target]);
    }

    private function shouldLogByDefault(LogLevel $level): bool
    {
        return $level->value >= $this->logLevel->value;
    }
}
