<?php

namespace Startcode\Runner\Presenter\Formatter;

class Basic extends FormatterAbstract
{

    public function format() : array
    {
        return $this->getBasicData()
            + $this->getProfilerData();
    }
}
