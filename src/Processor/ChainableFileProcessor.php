<?php
declare(strict_types=1);

namespace Atompulse\Logger\Processor;

use Atompulse\Logger\Processor\Traits\ChainableProcessorTrait;

class ChainableFileProcessor extends FileProcessor implements ChainableProcessorInterface
{
    use ChainableProcessorTrait;
}
