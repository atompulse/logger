<?php
declare(strict_types=1);

namespace Atompulse\Tests\Logger\Processor;

use Atompulse\Logger\LogEntry;
use Atompulse\Logger\LogLevel;
use Atompulse\Logger\Processor\ChainableConsoleProcessor;
use Atompulse\Logger\Processor\ChainableFileProcessor;
use Codeception\Stub;
use Codeception\Test\Unit;
use DateTimeImmutable;

class ChainableProcessorTest extends Unit
{
    public function testChainableConsoleProcessorChainsCorrectly()
    {
        $mockNextProcessor = Stub::makeEmpty(ChainableConsoleProcessor::class, [
            'log' => function ($logEntry) {
                $this->assertEquals('Test message', $logEntry->message);
            },
        ]);

        $processor = new ChainableConsoleProcessor(LogLevel::Debug);
        $processor->setNext($mockNextProcessor);

        $logEntry = new LogEntry('Test message', LogLevel::Debug, new DateTimeImmutable());
        $processor->log($logEntry);
    }

    public function testChainableFileProcessorChainsCorrectly()
    {
        $filepath = sys_get_temp_dir() . '/chain_log_test.txt';
        $mockNextProcessor = Stub::makeEmpty(ChainableFileProcessor::class, [
            'log' => function ($logEntry) use ($filepath) {
                file_put_contents($filepath, $logEntry->message);
            },
        ]);

        $processor = new ChainableFileProcessor(LogLevel::Debug, $filepath);
        $processor->setNext($mockNextProcessor);

        $logEntry = new LogEntry('Test message', LogLevel::Debug, new DateTimeImmutable());
        $processor->log($logEntry);

        $this->assertEquals('Test message', file_get_contents($filepath));

        // Cleanup
        unlink($filepath);
    }
}
