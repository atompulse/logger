<?php

use Atompulse\Logger\ChainedLogger;
use Atompulse\Logger\Processor\ChainableConsoleProcessor;
use Atompulse\Logger\Processor\ChainableFileProcessor;
use Atompulse\Logger\LogLevel;

require_once 'vendor/autoload.php';

$logger = new ChainedLogger(LogLevel::Debug);
$logger->addProcessor('file1', new ChainableFileProcessor(LogLevel::Error, 'var/logs/chained-file1-log.txt'));
$logger->addProcessor('file2', new ChainableFileProcessor(LogLevel::Warning, 'var/logs/chained-file2-log.txt'));
$logger->addProcessor('console', new ChainableConsoleProcessor(LogLevel::Debug));

$logger->log("This is a debug message", LogLevel::Debug);
$logger->log("This is an error message", LogLevel::Error);

$logger->setLogLevel(LogLevel::Warning);

$logger->log("This is a debug message", LogLevel::Debug);
$logger->log("This is an info message", LogLevel::Info);
$logger->log("This is an error message", LogLevel::Error);

$logger->setLogLevel(LogLevel::Error);

$logger->log("This is a debug message", LogLevel::Debug);
$logger->log("This is an info message", LogLevel::Info);
$logger->log("This is a warning message", LogLevel::Warning);
$logger->log("This is an error message", LogLevel::Error, ['with' => 'value']);

$logger->setLogLevel(LogLevel::Info);

$logger->setTargetLogLevel('file1', LogLevel::Info);
$logger->setTargetLogLevel('file2', LogLevel::Info);

$logger->log("This is a debug message", LogLevel::Debug);
$logger->log("This is an info message", LogLevel::Info);
$logger->log("This is a warning message", LogLevel::Warning);
$logger->log("This is an error message", LogLevel::Error, ['x' => 'y']);

$logger->removeProcessor('file2');

$logger->log("This is an info message X", LogLevel::Info);