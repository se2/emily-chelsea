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
use FSProVendor\Monolog\Formatter\LineFormatter;
/**
 * Helper trait for implementing FormattableInterface
 *
 * @author Jordi Boggiano <j.boggiano@seld.be>
 */
trait FormattableHandlerTrait
{
    /**
     * @var ?FormatterInterface
     */
    protected $formatter;
    /**
     * {@inheritDoc}
     */
    public function setFormatter(\FSProVendor\Monolog\Formatter\FormatterInterface $formatter) : \FSProVendor\Monolog\Handler\HandlerInterface
    {
        $this->formatter = $formatter;
        return $this;
    }
    /**
     * {@inheritDoc}
     */
    public function getFormatter() : \FSProVendor\Monolog\Formatter\FormatterInterface
    {
        if (!$this->formatter) {
            $this->formatter = $this->getDefaultFormatter();
        }
        return $this->formatter;
    }
    /**
     * Gets the default formatter.
     *
     * Overwrite this if the LineFormatter is not a good default for your handler.
     */
    protected function getDefaultFormatter() : \FSProVendor\Monolog\Formatter\FormatterInterface
    {
        return new \FSProVendor\Monolog\Formatter\LineFormatter();
    }
}
