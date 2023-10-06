<?php
declare(strict_types=1);

namespace Atompulse\Logger;

enum LogLevel: int
{
    case Debug = 1;
    case Info = 2;
    case Warning = 3;
    case Error = 4;
}
