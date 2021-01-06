<?php

namespace Startcode\Runner\Presenter;

use Startcode\CleanCore\Constants\Http;
use Startcode\Http\Response as HttpResponse;

class Renderer
{

    private ContentType $contentType;
    private DataTransfer $dataTransfer;

    private $body;
    private $httpResponse;


    public function __construct($body, ContentType $contentType, DataTransfer $dataTransfer)
    {
        $this->body         = $body;
        $this->contentType  = $contentType;
        $this->dataTransfer = $dataTransfer;
    }

    public function render() : void
    {
        $this->getHttpResponse()
            ->setHttpResponseCode($this->httpResponseCode())
            ->setBody($this->body)
            ->setHeader('Content-Type', $this->contentType->getContentType(), true)
            ->sendHeaders()
            ->sendResponse();
    }

    private function getHttpResponse() : HttpResponse
    {
        if (!$this->httpResponse instanceof HttpResponse) {
            $this->httpResponse = new HttpResponse();
        }
        return $this->httpResponse;
    }

    private function httpResponseCode() : int
    {
        return $this->dataTransfer->getResponse()->getHttpResponseCode() == Http::CODE_204
            ? Http::CODE_200
            : $this->dataTransfer->getResponse()->getHttpResponseCode();
    }
}
