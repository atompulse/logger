<?php

use Atompulse\Logger\Processor\ConsoleProcessor;
use Atompulse\Logger\Processor\FileProcessor;
use Atompulse\Logger\Logger;
use Atompulse\Logger\LogLevel;

require_once 'vendor/autoload.php';

$logger = new Logger(LogLevel::Debug);
$logger->addProcessor('file', new FileProcessor(LogLevel::Error, 'var/logs/file-log.txt'));
$logger->addProcessor('console', new ConsoleProcessor(LogLevel::Debug));

$logger->log("This is a debug message", LogLevel::Debug);
$logger->log("This is an error message", LogLevel::Error);

$logger->setLogLevel(LogLevel::Warning);

$logger->log("This should not be logged", LogLevel::Debug);
$logger->log("This should not be logged", LogLevel::Info);
$logger->log("This is should be logged as an error message", LogLevel::Error);

$logger->setLogLevel(LogLevel::Error);

$logger->log("This should not be logged", LogLevel::Debug);
$logger->log("This should not be logged", LogLevel::Info);
$logger->log("This should not be logged as warning", LogLevel::Warning);
$logger->log("This is should be logged as an error message", LogLevel::Error, ['with' => 'value']);


$logger->setTargetLogLevel('file', LogLevel::Warning);

$logger->log("This should not be logged", LogLevel::Debug);
$logger->log("This should not be logged", LogLevel::Info);
$logger->log("This should be logged", LogLevel::Warning);
$logger->log("This is should be logged as an error message", LogLevel::Error, ['x' => 'y']);
