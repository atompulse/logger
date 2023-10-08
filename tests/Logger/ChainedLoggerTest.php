<?php
declare(strict_types=1);

namespace Atompulse\Tests\Logger;

use Atompulse\Logger\ChainedLogger;
use Atompulse\Logger\LogEntry;
use Atompulse\Logger\LogLevel;
use Atompulse\Logger\Processor\ChainableProcessorInterface;
use Codeception\Stub;
use Codeception\Test\Unit;

class ChainedLoggerTest extends Unit
{
    public function testChainedLoggerSendsLogsToProcessor()
    {
        $mockProcessor = Stub::makeEmpty(ChainableProcessorInterface::class, [
            'log' => function ($logEntry) {
                $this->assertEquals('Test message', $logEntry->message);
            },
            'getLogLevel' => function () {
                return LogLevel::Debug;
            },
        ]);

        $logger = new ChainedLogger(LogLevel::Debug);
        $logger->addProcessor('mock', $mockProcessor);
        $logger->log('Test message', LogLevel::Debug);
    }

    public function testChainedLoggerRespectsLogLevel()
    {
        $mockProcessor = Stub::makeEmpty(ChainableProcessorInterface::class, [
            'log' => function () {
                $this->fail('Should not log messages below the set log level.');
            },
            'getLogLevel' => function () {
                return LogLevel::Error;
            },
        ]);

        $logger = new ChainedLogger(LogLevel::Error);
        $logger->addProcessor('mock', $mockProcessor);

        $logger->log('Debug message', LogLevel::Debug);
        $logger->log('Info message', LogLevel::Info);
        $logger->log('Warning message', LogLevel::Warning);
    }

    public function testChainedLoggerPassesLogToNextProcessor()
    {
        $wasPassedToProcessor2 = false;

        $mockProcessor1 = Stub::makeEmpty(ChainableProcessorInterface::class, [
            'log' => function (LogEntry $logEntry) use (&$wasPassedToProcessor2) {
                // This processor should not log the message but should pass it to the next processor
                if ($logEntry->level === LogLevel::Debug) {
                    $wasPassedToProcessor2 = true;
                }
            },
            'getLogLevel' => function () {
                return LogLevel::Error;
            },
        ]);

        $mockProcessor2 = Stub::makeEmpty(ChainableProcessorInterface::class, [
            'log' => function (LogEntry $logEntry) use (&$wasPassedToProcessor2) {
                // This processor should log the message
                if ($logEntry->level === LogLevel::Debug && $wasPassedToProcessor2) {
                    $this->assertEquals('Debug message', $logEntry->message);
                }
            },
            'getLogLevel' => function () {
                return LogLevel::Debug;
            },
        ]);

        $logger = new ChainedLogger();
        $logger->addProcessor('mock1', $mockProcessor1);
        $logger->addProcessor('mock2', $mockProcessor2);
        $mockProcessor1->setNext($mockProcessor2);

        // This will bypass mockProcessor1 and get logged by mockProcessor2
        $logger->log('Debug message', LogLevel::Debug);

        // This will not be logged by any processor
        $logger->log('Info message', LogLevel::Info);
    }

    public function testChainedLoggerRemovesProcessor()
    {
        $mockProcessor1 = Stub::makeEmpty(ChainableProcessorInterface::class, [
            'log' => function (LogEntry $logEntry) {
                $this->fail('Processor1 should not log messages after being removed.');
            },
            'getLogLevel' => function () {
                return LogLevel::Debug;
            },
        ]);

        $mockProcessor2 = Stub::makeEmpty(ChainableProcessorInterface::class, [
            'log' => function (LogEntry $logEntry) {
                $this->assertEquals('Debug message', $logEntry->message);
            },
            'getLogLevel' => function () {
                return LogLevel::Debug;
            },
        ]);

        $logger = new ChainedLogger();
        $logger->addProcessor('mock1', $mockProcessor1);
        $logger->addProcessor('mock2', $mockProcessor2);
        $mockProcessor1->setNext($mockProcessor2);

        // Remove mockProcessor1
        $logger->removeProcessor('mock1');

        // This will only be logged by mockProcessor2 since mockProcessor1 has been removed
        $logger->log('Debug message', LogLevel::Debug);
    }
}
