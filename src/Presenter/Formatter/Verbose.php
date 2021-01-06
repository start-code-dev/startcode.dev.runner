<?php

namespace Startcode\Runner\Presenter\Formatter;

class Verbose extends FormatterAbstract
{

    public function format() : array
    {
        return $this->getBasicData()
            + $this->getVerboseData()
            + $this->getProfilerData();
    }

    private function getBodyId() : string
    {
        return implode(' ', [
            $this->getDataTransfer()->getRequest()->getModule(),
            $this->getDataTransfer()->getRequest()->getResourceName(),
            $this->getDataTransfer()->getRequest()->getMethod(),
        ]);
    }

    private function getVerboseData() : array
    {
        return [
            'app_code'      => $this->getDataTransfer()->getResponse()->getApplicationResponseCode(),
            'app_message'   => $this->getDataTransfer()->getResponse()->getResponseMessage(),
            'params'        => $this->getDataTransfer()->getRequest()->getParamsAnonymized(),
            'method'        => $this->getDataTransfer()->getRequest()->getMethod(),
            'resource_name' => $this->getDataTransfer()->getRequest()->getResourceName(),
            'body_id'       => $this->getBodyId(),
        ];
    }
}
