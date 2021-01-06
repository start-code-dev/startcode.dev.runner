<?php

namespace Startcode\Runner;

use Startcode\Http\Request;

interface RunnerInterface
{
    public function bootstrap();

    public function run();

    public function getHttpRequest() : Request;

    public function getApplication();

    public function getApplicationModule();

    public function getApplicationService();

    public function getApplicationMethod();

    public function getApplicationParams();

}
