<?php

namespace Startcode\Runner\Presenter\Formatter;

use Startcode\CleanCore\Constants\Override;
use Startcode\Runner\Presenter\DataTransfer;

abstract class FormatterAbstract implements FormatterInterface
{

    /**
     * @var DataTransfer
     */
    private $dataTransfer;

    private array $formatted;


    public function __construct()
    {
        $this->formatted = [];
    }

    public function getBasicData() : array
    {
        return [
            'code'     => $this->getDataTransfer()->getResponse()->getHttpResponseCode(),
            'message'  => $this->getDataTransfer()->getResponse()->getHttpMessage(),
            'response' => $this->getDataTransfer()->getResponse()->getResponseObject(),
            'env'      => defined(APPLICATION_ENV) ? APPLICATION_ENV : null,
        ];
    }

    public function getDataTransfer() : DataTransfer
    {
        return $this->dataTransfer;
    }

    public function getProfilerData() : array
    {
        return $this->isProfilerEnabled()
            ? ['profiler' =>  $this->getDataTransfer()->getProfiler()->getProfilerOutput()]
            : [];
    }

    public function setDataTransfer(DataTransfer $dataTransfer) : self
    {
        $this->dataTransfer = $dataTransfer;
        return $this;
    }

    private function isProfilerEnabled() : bool
    {
        return $this->getDataTransfer()->getHttpRequest()->has(Override::DB_PROFILER)
            && $this->getDataTransfer()->getHttpRequest()->get(Override::DB_PROFILER) == 1;
    }
}
