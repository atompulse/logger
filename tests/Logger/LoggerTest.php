<?php
declare(strict_types=1);

namespace Atompulse\Tests\Logger;

use Atompulse\Logger\Logger;
use Atompulse\Logger\LogLevel;
use Atompulse\Logger\Processor\LogProcessorInterface;
use Codeception\Stub;
use Codeception\Test\Unit;

class LoggerTest extends Unit
{
    public function testLoggerSendsLogsToProcessor()
    {
        $mockProcessor = Stub::makeEmpty(LogProcessorInterface::class, [
            'log' => function ($logEntry) {
                $this->assertEquals('Test message', $logEntry->message);
            },
            'getLogLevel' => function() {
                return LogLevel::Debug;
            }
        ]);

        $logger = new Logger(LogLevel::Debug);
        $logger->addProcessor('mock', $mockProcessor);
        $logger->log('Test message', LogLevel::Debug);
    }

    public function testLoggerRespectsLogLevel()
    {
        $mockProcessor = Stub::makeEmpty(LogProcessorInterface::class, [
            'log' => function () {
                $this->fail('Should not log messages below the set log level.');
            },
            'getLogLevel' => function() {
                return LogLevel::Error;
            }
        ]);

        $logger = new Logger(LogLevel::Error);
        $logger->addProcessor('mock', $mockProcessor);

        $logger->log('Debug message', LogLevel::Debug);
        $logger->log('Info message', LogLevel::Info);
        $logger->log('Warning message', LogLevel::Warning);
    }

    public function testLoggerRespectsProcessorLogLevel()
    {
        $mockProcessor = Stub::makeEmpty(LogProcessorInterface::class, [
            'log' => function () {
                $this->fail('Should not log messages below the processor log level.');
            },
            'getLogLevel' => function() {
                return LogLevel::Error;
            }
        ]);

        $logger = new Logger(LogLevel::Debug);
        $logger->addProcessor('mock', $mockProcessor);
        $logger->setTargetLogLevel('mock', LogLevel::Error);

        $logger->log('Debug message', LogLevel::Debug);
        $logger->log('Info message', LogLevel::Info);
        $logger->log('Warning message', LogLevel::Warning);
    }
}
