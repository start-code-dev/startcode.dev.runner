<?php

namespace Startcode\Runner\Presenter\Formatter;

use Startcode\Runner\Presenter\DataTransfer;

interface FormatterInterface
{

    public function format();

    public function setDataTransfer(DataTransfer $dataTransfer);
}
