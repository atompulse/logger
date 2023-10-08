<?php
declare(strict_types=1);

namespace Atompulse\Tests\Logger\Processor;

use Atompulse\Logger\LogEntry;
use Atompulse\Logger\LogLevel;
use Atompulse\Logger\Processor\FileProcessor;
use Codeception\Test\Unit;
use DateTimeImmutable;

class FileProcessorTest extends Unit
{
    public function testFileProcessorLogsCorrectly()
    {
        $filepath = sys_get_temp_dir() . '/log_test.txt';
        $processor = new FileProcessor(LogLevel::Debug, $filepath);

        $logEntry = new LogEntry('Test message', LogLevel::Debug, new DateTimeImmutable());
        $expectedOutput = "[" . $logEntry->time->format(DateTimeImmutable::W3C) . "] [" . $logEntry->level->name . "] " . $logEntry->message . " [[]]\n";

        $processor->log($logEntry);

        $this->assertTrue(file_exists($filepath), "Log file was not created.");
        $this->assertEquals($expectedOutput, file_get_contents($filepath));

        // Cleanup
        unlink($filepath);
    }

    public function testFileProcessorRespectsLogLevel()
    {
        $filepath = sys_get_temp_dir() . '/log_test.txt';
        $processor = new FileProcessor(LogLevel::Error, $filepath);

        $processor->log(new LogEntry('Debug message', LogLevel::Debug, new DateTimeImmutable()));

        if (file_exists($filepath)) {
            $this->assertEmpty(file_get_contents($filepath));
            // Cleanup
            unlink($filepath);
        } else {
            $this->assertTrue(true, "Log file was not created for a log level below the set level.");
        }
    }
}
