<?php
/**
 * Created by PhpStorm.
 * User: he110
 * Date: 2020-04-22
 * Time: 00:14
 */

namespace App\Traits;


use Psr\Log\LoggerInterface;

class LoggerTrait
{
    /** @var LoggerInterface */
    private $logger;

    /**
     * @return LoggerInterface
     */
    public function getLogger(): LoggerInterface
    {
        return $this->logger;
    }

    /**
     * @required
     * @param LoggerInterface $logger
     * @return LoggerTrait
     */
    public function setLogger(LoggerInterface $logger): self
    {
        $this->logger = $logger;
        return $this;
    }

    public function logDebug(string $message, array $context = array()): void
    {
        if ($this->getLogger())
            $this->getLogger()->debug($message, $context);
    }

    public function logInfo(string $message, array $context = array()): void
    {
        if ($this->getLogger())
            $this->getLogger()->info($message, $context);
    }

    public function logNotice(string $message, array $context = array()): void
    {
        if ($this->getLogger())
            $this->getLogger()->notice($message, $context);
    }

    public function logWarning(string $message, array $context = array()): void
    {
        if ($this->getLogger())
            $this->getLogger()->warning($message, $context);
    }

    public function logError(string $message, array $context = array()): void
    {
        if ($this->getLogger())
            $this->getLogger()->error($message, $context);
    }

    public function logCritical(string $message, array $context = array()): void
    {
        if ($this->getLogger())
            $this->getLogger()->critical($message, $context);
    }

    public function logAlert(string $message, array $context = array()): void
    {
        if ($this->getLogger())
            $this->getLogger()->alert($message, $context);
    }

    public function logEmergency(string $message, array $context = array()): void
    {
        if ($this->getLogger())
            $this->getLogger()->emergency($message, $context);
    }
}