<?php

namespace Startcode\Runner\Presenter;

use Startcode\CleanCore\Constants\{Format, ContentType as ContentTypeConst};

class ContentType
{


    private DataTransfer $dataTransfer;

    private HeaderAccept $headerAccept;

    private string $format;

    public function __construct(DataTransfer $dataTransfer, HeaderAccept $headerAccept)
    {
        $this->dataTransfer = $dataTransfer;
        $this->headerAccept = $headerAccept;
        $this->formatFactory();
    }

    public function getContentType() : string
    {
        return $this->contentTypeMap()[$this->getFormat()];
    }

    public function getDataTransfer() : DataTransfer
    {
        return $this->dataTransfer;
    }

    public function getFormat() : string
    {
        return $this->format;
    }

    private function isFormatValid() : bool
    {
        return in_array($this->format, [
            Format::JSON,
            Format::TWIG,
        ]);
    }

    private function contentTypeMap() : array
    {
        return [
            Format::JSON => ContentTypeConst::JSON,
            Format::TWIG => ContentTypeConst::HTML,
        ];
    }

    /**
     * @throws \Exception
     */
    private function formatFactory()
    {
        $format = $this->dataTransfer->getResponse()->getResponseObjectPart(Format::FORMAT);
        $this->format = $format === null ? $this->headerAccept->getFormat() : $format;
        if (! $this->isFormatValid()) {
            throw new \Exception('Not valid runner view format!', 600);
        }
    }
}
