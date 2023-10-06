<?php
declare(strict_types=1);

namespace Atompulse\Logger\Processor;

interface ChainableProcessorInterface extends LogProcessorInterface
{
    public function setNext(ChainableProcessorInterface $nextProcessor): void;
}
