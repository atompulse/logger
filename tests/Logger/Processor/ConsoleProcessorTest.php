<?php
declare(strict_types=1);

namespace Atompulse\Tests\Logger\Processor;

use Atompulse\Logger\LogEntry;
use Atompulse\Logger\LogLevel;
use Atompulse\Logger\Processor\ConsoleProcessor;
use Codeception\Test\Unit;
use DateTimeImmutable;

class ConsoleProcessorTest extends Unit
{
    public function testConsoleProcessorLogsCorrectly()
    {
        $processor = new ConsoleProcessor(LogLevel::Debug);

        $logEntry = new LogEntry('Test message', LogLevel::Debug, new DateTimeImmutable());
        $expectedOutput = "[" . $logEntry->time->format(DateTimeImmutable::W3C) . "] [" . $logEntry->level->name . "] " . $logEntry->message . " [[]]\n";

        // Capture the output
        ob_start();
        $processor->log($logEntry);
        $output = ob_get_clean();

        $this->assertEquals($expectedOutput, $output);
    }

    public function testConsoleProcessorRespectsLogLevel()
    {
        $processor = new ConsoleProcessor(LogLevel::Error);

        // Capture the output
        ob_start();
        $processor->log(new LogEntry('Debug message', LogLevel::Debug, new DateTimeImmutable()));
        $output = ob_get_clean();

        $this->assertEmpty($output);
    }
}
