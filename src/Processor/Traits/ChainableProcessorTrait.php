<?php
declare(strict_types=1);

namespace Atompulse\Logger\Processor\Traits;

use Atompulse\Logger\LogEntry;
use Atompulse\Logger\Processor\ChainableProcessorInterface;

trait ChainableProcessorTrait
{
    private ?ChainableProcessorInterface $nextProcessor = null;

    public function setNext(ChainableProcessorInterface $nextProcessor): void
    {
        $this->nextProcessor = $nextProcessor;
    }

    public function log(LogEntry $log): void
    {
        parent::log($log);

        $this->callNext($log);
    }

    private function callNext(LogEntry $log): void
    {
        if ($this->nextProcessor) {
            $this->nextProcessor->log($log);
        }
    }
}
