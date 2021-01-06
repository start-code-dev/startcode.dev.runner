<?php

namespace Startcode\Runner\Presenter;

use Startcode\Http\Request as HttpRequest;
use Startcode\Runner\Profiler;
use Startcode\CleanCore\Request\Request;
use Startcode\CleanCore\Response\Response;

class DataTransfer
{


    private HttpRequest $httpRequest;

    private Profiler $profiler;

    private Request $request;

    private Response $response;


    public function __construct(HttpRequest $httpRequest, Profiler $profiler, Request $request, Response $response)
    {
        $this->httpRequest  = $httpRequest;
        $this->profiler     = $profiler;
        $this->request      = $request;
        $this->response     = $response;
    }

    public function getHttpRequest() : HttpRequest
    {
        return $this->httpRequest;
    }

    public function getProfiler() : Profiler
    {
        return $this->profiler;
    }

    public function getRequest() : Request
    {
        return $this->request;
    }

    public function getResponse() : Response
    {
        return $this->response;
    }
}
