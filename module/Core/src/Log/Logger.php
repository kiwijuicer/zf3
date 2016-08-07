<?php
namespace Core\Log;

use Core\Log\Exception\PhpException;

/**
 * Logger
 *
 * @package Core\Log
 */
class Logger extends \Zend\Log\Logger
{
    /**
     * Register logging system as an error handler to log PHP errors
     *
     * @link http://www.php.net/manual/function.set-error-handler.php
     * @param  Logger $logger
     * @param  bool   $continueNativeHandler
     * @return mixed  Returns result of set_error_handler
     * @throws \InvalidArgumentException if logger is null
     */
    public static function registerErrorHandler(\Zend\Log\Logger $logger, $continueNativeHandler = false)
    {
        // Only register once per instance
        if (static::$registeredErrorHandler) {
            return false;
        }

        $errorPriorityMap = static::$errorPriorityMap;

        $error_handler = function ($level, $message, $file, $line) use ($logger, $errorPriorityMap, $continueNativeHandler) {

            if ($level > E_ERROR && error_reporting() == 0) {
                return;
            }

            if (error_reporting() & $level || $level == E_ERROR) {

                $exception = new PhpException($message, $level, $file, $line);

                if (isset($errorPriorityMap[$level])) {
                    $priority = $errorPriorityMap[$level];
                } else {
                    $priority = Logger::INFO;
                }

                $logger->log($priority, $exception);
                if ($level != E_ERROR) {
                    // Only throw if $errno is NOT a fatal php error (E_ERROR)
                    throw $exception;
                }

            }

            return !$continueNativeHandler;
        };

        $previous = set_error_handler($error_handler);

        register_shutdown_function(function() use($error_handler) {

            $error = error_get_last();

            if ($error['type'] == E_ERROR) {
                $error_handler($error['type'], $error['message'], $error['file'], $error['line']);
            }

        });

        static::$registeredErrorHandler = true;
        return $previous;
    }

}