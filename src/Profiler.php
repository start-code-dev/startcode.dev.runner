<?php

namespace Startcode\Runner;

use Startcode\Profiler\Ticker\TickerAbstract;

class Profiler
{

    private ?array $formatted = null;
    private array $profilers;


    public function __construct()
    {
        $this->profilers = [];
        $this->formatted = [];
    }

    public function addProfiler(TickerAbstract $profiler) : self
    {
        $this->profilers[] = $profiler;
        return $this;
    }

    public function getProfilerOutput() : array
    {
        return $this->hasProfilers()
            ? $this->getFormatted()
            : [];
    }

    private function getFormatted() : array
    {
        if (!$this->hasFormatted()) {
            $this->formatted = [];
            foreach($this->profilers as $profiler) {
                $this->formatted[$profiler->getName()] = $profiler->getFormatted();
            }
        }
        return $this->formatted;
    }

    private function hasFormatted() : bool
    {
        return $this->formatted !== null;
    }

    private function hasProfilers() : bool
    {
        return !empty($this->profilers);
    }
}
