<?php

namespace Startcode\Runner;

use Startcode\CleanCore\Request\Request;
use Startcode\CleanCore\Application as CleanCoreApplication;

class Application extends CleanCoreApplication
{
    public function __construct(RunnerInterface $appRunner = null)
    {
        if(null !== $appRunner && $appRunner instanceof RunnerInterface) {
            $request = new Request();
            $request
                ->setModule($appRunner->getApplicationModule())
                ->setMethod($appRunner->getApplicationMethod())
                ->setResourceName($appRunner->getApplicationService())
                ->setParams($appRunner->getApplicationParams())
                ->setAjaxCall($appRunner->getHttpRequest()->isAjax())
                ->setRawInput(file_get_contents("php://input"))
                ->setServerVariables($_SERVER);

            // set anonymization rules for sensitive parameters
            foreach ($this->getRequestAnonymizationRules() as $param => $rule) {
                $request->setAnonymizationRules($param, $rule);
            }

            $this
                ->setRequest($request)
                ->setAppNamespace($appRunner->getApplicationModule());
        }
    }

    private function getRequestAnonymizationRules() : array
    {
        return [
            'X-ND-Authentication' => function($value) {
                return substr_replace($value, str_repeat('*', 20), 0, -12);
            },
            'X-ND-AppKey' => function($value) {
                return substr_replace($value, str_repeat('*', 20), 0, -12);
            },
            'card_number' => function($value) {
                return substr_replace($value, str_repeat('*', 12), 0, -4);
            },
            'card_cvv2'     => '***',
            'session'       => null,
            '__site_id'     => null,
            '__system_type' => null,
            'image'         => null,
        ];
    }
}
