<?php

namespace Startcode\Runner;

use Startcode\CleanCore\Constants\Http;

use LogLogger;
use Cli;
use Startcode\Http\Request;
use Startcode\Profiler\Ticker\TickerAbstract;
use Startcode\Runner\Presenter\DataTransfer;
use Startcode\Runner\Presenter\Formatter\FormatterInterface;
use Startcode\Runner\Presenter\{ContentType, HeaderAccept};

abstract class RunnerAbstract implements RunnerInterface
{

    /**
     * @var \Startcode\CleanCore\Application
     */
    private $application;

    /**
     * @var string
     */
    private $applicationMethod;

    /**
     * @var Cli
     */
    private $commando;

    /**
     * @var HeaderAccept
     */
    private $headerAccept;

    /**
     * @var Request
     */
    private $httpRequest;

    /**
     * @var Logger
     */
    private $logger;

    /**
     * @var Profiler
     */
    private $profiler;

    /**
     * @var ResponseFormatter
     */
    private $responseFormatter;

    /**
     * @var array
     */
    private $routerOptions;

    public function __construct($headerAccept = null)
    {
        $this->profiler          = new Profiler();
        $this->logger            = new Logger();
        $this->responseFormatter = new ResponseFormatter();
        $this->headerAccept = $headerAccept
            ?: new HeaderAccept();
    }

    public function getApplication()
    {
        return $this->application;
    }

    public function getApplicationMethod()
    {
        return $this->applicationMethod;
    }

    public function getApplicationModule()
    {
        return ucwords($this->routerOptions['module']);
    }

    public function getApplicationParams()
    {
        return $this->getHttpRequest()->isCli()
            ? json_decode($this->commando->value('params'), true)
            : $this->getReqParams();
    }

    public function getApplicationService() : string
    {
        return ucwords($this->routerOptions['service']);
    }

    public function getHttpRequest() : Request
    {
        if(null === $this->httpRequest) {
            $this->httpRequest = new Request();
        }
        return $this->httpRequest;
    }

    public function registerProfilerTicker(TickerAbstract $profiler) : self
    {
        $this->profiler->addProfiler($profiler);
        return $this;
    }

    public function registerRequestLogger(LogLogger $logger) : self
    {
        $this->logger->setLogger($logger);
        return $this;
    }

    public function registerFormatterBasic(FormatterInterface $formatter)  : self
    {
        $this->responseFormatter->addBasic($formatter);
        return $this;
    }

    public function registerFormatterVerbose(FormatterInterface $formatter) : self
    {
        $this->responseFormatter->addVerbose($formatter);
        return $this;
    }

    public final function run() : void
    {
        $this->route();
        $this->parseApplicationMethod();

        $this->application = new Application($this);

        $this->logger->logRequest($this->application);

        $this->application->run();

        $contentType = new ContentType($this->getDataTransfer(), $this->headerAccept);

        (new Presenter($this->responseFormatter, $contentType))->render();

         $this->logger->logResponse($this->application, $this->profiler);
    }

    public function setCommando(Cli $commando) : self
    {
        $this->commando = $commando;
        return $this;
    }

    private function getDataTransfer() : DataTransfer
    {
        return new DataTransfer(
            $this->getHttpRequest(),
            $this->profiler,
            $this->application->getRequest(),
            $this->application->getResponse());
    }

    private function getReqParams()
    {
        $params = $this->getHttpRequest()->getParams();
        if(isset($this->routerOptions['url_part']) && is_array($params)){
            $params['url_part'] = $this->routerOptions['url_part'];
        }
        return $params;
    }

    private function parseApplicationMethod() : self
    {
        if ($this->getHttpRequest()->isCli()) {
            $params = $this->commando->has('params')
                ? json_decode($this->commando->value('params'), true)
                : [];
            $method = $this->commando->has('method')
                ? strtoupper($this->commando->value('method'))
                : null;
            $id = $params['id'] ?? null;
        } else {
            $method = $this->getHttpRequest()->getMethod();
            $id     = $this->getHttpRequest()->getParam('id');
        }

        $this->applicationMethod = ($method == Http::METHOD_GET && empty($id))
            ? 'Index'
            : ucwords(strtolower($method));
        return $this;
    }

    private function route() : self
    {
        $this->routerOptions = !$this->getHttpRequest()->isCli()
            ? require_once PATH_CONFIG . '/routes.php'
            : [
                'module'  => $this->commando->value('module'),
                'service' => $this->commando->value('service'),
            ];
        return $this;
    }
}
