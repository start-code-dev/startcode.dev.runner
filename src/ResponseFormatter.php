<?php

namespace Startcode\Runner;

use Startcode\Runner\Presenter\Formatter\{FormatterInterface, Basic, Verbose};

class ResponseFormatter
{

    private $basic;
    private $verbose;

    public function __construct()
    {
    }

    public function addBasic(FormatterInterface $formatter) : void
    {
        $this->basic = $formatter;
    }

    public function addVerbose(FormatterInterface $formatter) : void
    {
        $this->verbose = $formatter;
    }

    public function getBasic() : FormatterInterface
    {
        return $this->basic instanceof FormatterInterface
            ? $this->basic
            : new Basic();
    }

    public function getVerbose() : FormatterInterface
    {
        return $this->verbose instanceof FormatterInterface
            ? $this->verbose
            : new Verbose();
    }
}
