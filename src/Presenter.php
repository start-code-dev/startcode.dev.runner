<?php

namespace Startcode\Runner;

use Startcode\Runner\Presenter\{DataTransfer, Formatter, View, Renderer, ContentType};

class Presenter
{

    private DataTransfer $dataTransfer;

    private ContentType $contentType;

    private ResponseFormatter $responseFormatter;


    public function __construct(ResponseFormatter $responseFormatter, ContentType $contentType)
    {
        $this->dataTransfer      = $contentType->getDataTransfer();
        $this->responseFormatter = $responseFormatter;
        $this->contentType       = $contentType;
    }

    public function render() : void
    {
        (new Renderer($this->getFormattedBody(), $this->contentType, $this->dataTransfer))->render();
    }

    private function getFormattedBody() : string
    {
        return (new View($this->getFormattedData(), $this->contentType, $this->dataTransfer))->renderBody();
    }

    private function getFormattedData() : array
    {
        return (new Formatter($this->dataTransfer, $this->responseFormatter))->format();
    }
}
