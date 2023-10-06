<?php
declare(strict_types=1);

namespace Atompulse\Logger\Processor;

use Atompulse\Logger\Processor\Traits\LogFormatterTrait;
use Atompulse\Logger\Processor\Traits\SharedLogLevelProceduresTrait;
use Atompulse\Logger\LogEntry;
use Atompulse\Logger\LogLevel;
use RuntimeException;

class FileProcessor implements LogProcessorInterface
{
    use SharedLogLevelProceduresTrait;
    use LogFormatterTrait;

    public function __construct(
        private LogLevel $logLevel,
        private string $filepath,
    ) {
        $folder = dirname($filepath);

        if (!file_exists($folder) && !mkdir($folder, 0777, true)) {
            throw new RuntimeException("Unable to find or create path [$folder]");
        }
    }

    public function log(LogEntry $log): void
    {
        $this->writeLog($log);
    }

    private function writeLog(LogEntry $log)
    {
        if (file_put_contents(
                $this->filepath,
                $this->formatLog($log) . PHP_EOL,
                FILE_APPEND
            ) === false) {
            throw new RuntimeException("Unable to write to file [$this->filepath]");
        }
    }
}
