<?php

namespace Startcode\Runner\Presenter\View;

use Startcode\Runner\Presenter\DataTransfer;

abstract class ViewAbstract
{

    private array $data;

    private DataTransfer $dataTransfer;

    public function __construct(array $data, DataTransfer $dataTransfer)
    {
        $this->data         = $data;
        $this->dataTransfer = $dataTransfer;
    }

    public function getData() : array
    {
        return $this->data;
    }

    public function getDataTransfer() : DataTransfer
    {
        return $this->dataTransfer;
    }
}
