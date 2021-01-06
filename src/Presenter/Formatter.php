<?php

namespace Startcode\Runner\Presenter;

use Startcode\CleanCore\Constants\{Env, Override};
use Startcode\Runner\ResponseFormatter;
use Startcode\Runner\Presenter\Formatter\FormatterInterface;

class Formatter
{

    private DataTransfer $dataTransfer;

    private ResponseFormatter $responseFormatter;

    public function __construct(DataTransfer $dataTransfer, ResponseFormatter $responseFormatter)
    {
        $this->dataTransfer      = $dataTransfer;
        $this->responseFormatter = $responseFormatter;
    }

    public function format() : array
    {
        return $this->getFormatterInstance()
            ->setDataTransfer($this->dataTransfer)
            ->format();
    }

    private function getFormatterInstance() : FormatterInterface
    {
        return $this->shouldFormatVerbose()
            ? $this->responseFormatter->getVerbose()
            : $this->responseFormatter->getBasic();
    }

    private function shouldFormatVerbose() : bool
    {
        $overdide = $this->dataTransfer->getHttpRequest()->has(Override::VERBOSE_RESPONSE)
            && $this->dataTransfer->getHttpRequest()->get(Override::VERBOSE_RESPONSE) == 1;

        $debug = defined('DEBUG') && DEBUG;

        $env = defined('APPLICATION_ENV') && !in_array(APPLICATION_ENV, [Env::PRODUCTION, Env::BETA]);

        return $overdide || $debug || $env;
    }
}
