<?php
declare(strict_types=1);

namespace Application;

/**
 * Module
 *
 * @package Application
 */
class Module
{
    /**
     * Returns the config
     *
     * @return array
     */
    public function getConfig() : array
    {
        return include __DIR__ . '/../config/module.config.php';
    }
}
