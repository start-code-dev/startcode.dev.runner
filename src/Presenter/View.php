<?php

namespace Startcode\Runner\Presenter;

use Startcode\CleanCore\Constants\Format;
use Startcode\Runner\Presenter\View\{Json, Twig, ViewInterface};

class View
{

    private ContentType $contentType;

    private array $data;

    private DataTransfer $dataTransfer;


    public function __construct(array $data, ContentType $contentType, DataTransfer $dataTransfer)
    {
        $this->data         = $data;
        $this->contentType  = $contentType;
        $this->dataTransfer = $dataTransfer;
    }

    public function renderBody() : string
    {
        return $this->getViewInstance()->renderBody();
    }

    private function getViewInstance() : ViewInterface
    {
        switch ($this->contentType->getFormat()) {
            case Format::TWIG:
                return new Twig($this->data, $this->dataTransfer);
            case Format::JSON:
            default:
                return new Json($this->data, $this->dataTransfer);
        }
    }
}
