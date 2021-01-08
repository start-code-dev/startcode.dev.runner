<?php

namespace Startcode\Runner;

use Startcode\CleanCore\Application;
use Logger as LogLogger;
use Startcode\Profiler\Data\Request as ProfilerDataRequest;
use Startcode\Profiler\Data\Response as ProfilerDataResponse;

class Logger
{

    /**
     * @var LogLogger
     */
    private $logger;

    /**
     * @var float
     */
    private $startTime;

    /**
     * @var string
     */
    private $uniqueId;

    public function __construct()
    {
        $this->startTime = microtime(true);
        $this->uniqueId  = md5(uniqid($this->startTime, true));
    }

    public function setLogger(LogLogger $logger) : void
    {
        $this->logger = $logger;
    }

    public function logRequest(Application $application) : void
    {
        if ($this->isLoggerRegistered()) {
            register_shutdown_function([$this->logger, 'log'], $this->getDataForRequest($application));
        }
    }

    public function logResponse(Application $application, Profiler $profiler) : void
    {
        if ($this->isLoggerRegistered()) {
            register_shutdown_function([$this->logger, 'logAppend'], $this->getDataForResponse($application, $profiler));
        }
    }

    private function getDataForRequest(Application $application) : ProfilerDataRequest
    {
        $loggerData = new ProfilerDataRequest();
        $loggerData
            ->setApplication($application)
            ->setId($this->uniqueId)
            ->setParamsToObfuscate([Parameters::CC_NUMBER, Parameters::CC_CVV2, 'image']);
        return $loggerData;
    }

    private function getDataForResponse(Application $application, Profiler $profiler) : ProfilerDataResponse
    {
        $loggerData = new ProfilerDataResponse();
        $loggerData
            ->setApplication($application)
            ->setId($this->uniqueId)
            ->setStartTime($this->startTime)
            ->setProfiler($profiler);
        return $loggerData;
    }

    private function isLoggerRegistered() : bool
    {
        return $this->logger instanceof LogLogger;
    }
}
