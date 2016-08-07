<?php
declare(strict_types=1);

namespace Core\Log;

use Zend\Config\Config;
use Zend\Log\Logger;

/**
 * Logging
 *
 * The config gets set in the onBootstrap
 *
 * @package Base\Log
 * @author Norbert Hanauer <norbert.hanauer@check24.de>
 * @copyright CHECK24 Vergleichsportal GmbH
 */
class Logging {

    /**
     * The console logger identifier
     */
    const LOGGER_CONSOLE = 'Log\Console';

    /**
     * The application logger identifier
     */
    const LOGGER_APPLICATION = 'Log\Application';

    /**
     * Error handler logger
     */
    const LOGGER_ERROR_HANDLER = 'Log\ErrorHandler';

    /**
     * Exception handler logger
     */
    const LOGGER_EXCEPTION_HANDLER = 'Log\ExceptionHandler';

    /**
     * The logger identifier map
     */
    const LOGGER_LIST = [
        self::LOGGER_CONSOLE,
        self::LOGGER_APPLICATION,
        self::LOGGER_ERROR_HANDLER,
        self::LOGGER_EXCEPTION_HANDLER
    ];

    /**
     * Logger config (set in onBootstrap)
     *
     * @var Config
     */
    private static $config;

    /**
     * The logger stack
     *
     * @var Logger[]
     */
    private static $stack = [];

    /**
     * Returns the requested logger
     *
     * @param string $loggerIdentifier
     * @return Logger
     */
    public static function getLogger(string $loggerIdentifier) : Logger
    {
        if (!in_array($loggerIdentifier, self::LOGGER_LIST, true)) {
            throw new \InvalidArgumentException('Invalid logger identifier given (' . $loggerIdentifier . ')');
        }

        if (!array_key_exists($loggerIdentifier, self::$stack)) {
            self::$stack[$loggerIdentifier] = new Logger(self::$config->{$loggerIdentifier});
        }
        return self::$stack[$loggerIdentifier];
    }

    /**
     * Sets the logging config
     *
     * @param \Zend\Config\Config $config
     * @return void
     */
    public static function setConfig(Config $config)
    {
        self::$config = $config;
    }
}