<?php
declare(strict_types=1);

namespace Atompulse\Logger\Processor;

use Atompulse\Logger\Processor\Traits\ChainableProcessorTrait;

class ChainableConsoleProcessor extends ConsoleProcessor implements ChainableProcessorInterface
{
    use ChainableProcessorTrait;
}
