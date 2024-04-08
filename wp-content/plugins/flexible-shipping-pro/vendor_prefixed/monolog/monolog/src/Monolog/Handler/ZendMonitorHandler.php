<?php

declare (strict_types=1);
/*
 * This file is part of the Monolog package.
 *
 * (c) Jordi Boggiano <j.boggiano@seld.be>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace FSProVendor\Monolog\Handler;

use FSProVendor\Monolog\Formatter\FormatterInterface;
use FSProVendor\Monolog\Formatter\NormalizerFormatter;
use FSProVendor\Monolog\Logger;
/**
 * Handler sending logs to Zend Monitor
 *
 * @author  Christian Bergau <cbergau86@gmail.com>
 * @author  Jason Davis <happydude@jasondavis.net>
 *
 * @phpstan-import-type FormattedRecord from AbstractProcessingHandler
 */
class ZendMonitorHandler extends \FSProVendor\Monolog\Handler\AbstractProcessingHandler
{
    /**
     * Monolog level / ZendMonitor Custom Event priority map
     *
     * @var array<int, int>
     */
    protected $levelMap = [];
    /**
     * @throws MissingExtensionException
     */
    public function __construct($level = \FSProVendor\Monolog\Logger::DEBUG, bool $bubble = \true)
    {
        if (!\function_exists('FSProVendor\\zend_monitor_custom_event')) {
            throw new \FSProVendor\Monolog\Handler\MissingExtensionException('You must have Zend Server installed with Zend Monitor enabled in order to use this handler');
        }
        //zend monitor constants are not defined if zend monitor is not enabled.
        $this->levelMap = [\FSProVendor\Monolog\Logger::DEBUG => \FSProVendor\ZEND_MONITOR_EVENT_SEVERITY_INFO, \FSProVendor\Monolog\Logger::INFO => \FSProVendor\ZEND_MONITOR_EVENT_SEVERITY_INFO, \FSProVendor\Monolog\Logger::NOTICE => \FSProVendor\ZEND_MONITOR_EVENT_SEVERITY_INFO, \FSProVendor\Monolog\Logger::WARNING => \FSProVendor\ZEND_MONITOR_EVENT_SEVERITY_WARNING, \FSProVendor\Monolog\Logger::ERROR => \FSProVendor\ZEND_MONITOR_EVENT_SEVERITY_ERROR, \FSProVendor\Monolog\Logger::CRITICAL => \FSProVendor\ZEND_MONITOR_EVENT_SEVERITY_ERROR, \FSProVendor\Monolog\Logger::ALERT => \FSProVendor\ZEND_MONITOR_EVENT_SEVERITY_ERROR, \FSProVendor\Monolog\Logger::EMERGENCY => \FSProVendor\ZEND_MONITOR_EVENT_SEVERITY_ERROR];
        parent::__construct($level, $bubble);
    }
    /**
     * {@inheritDoc}
     */
    protected function write(array $record) : void
    {
        $this->writeZendMonitorCustomEvent(\FSProVendor\Monolog\Logger::getLevelName($record['level']), $record['message'], $record['formatted'], $this->levelMap[$record['level']]);
    }
    /**
     * Write to Zend Monitor Events
     * @param string $type      Text displayed in "Class Name (custom)" field
     * @param string $message   Text displayed in "Error String"
     * @param array  $formatted Displayed in Custom Variables tab
     * @param int    $severity  Set the event severity level (-1,0,1)
     *
     * @phpstan-param FormattedRecord $formatted
     */
    protected function writeZendMonitorCustomEvent(string $type, string $message, array $formatted, int $severity) : void
    {
        zend_monitor_custom_event($type, $message, $formatted, $severity);
    }
    /**
     * {@inheritDoc}
     */
    public function getDefaultFormatter() : \FSProVendor\Monolog\Formatter\FormatterInterface
    {
        return new \FSProVendor\Monolog\Formatter\NormalizerFormatter();
    }
    /**
     * @return array<int, int>
     */
    public function getLevelMap() : array
    {
        return $this->levelMap;
    }
}
