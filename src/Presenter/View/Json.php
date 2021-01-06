<?php

namespace Startcode\Runner\Presenter\View;

use Startcode\CleanCore\Constants\Override;

class Json extends ViewAbstract implements ViewInterface
{

    public function renderBody() : string
    {
        return json_encode($this->getData(), $this->encodeOptions());
    }

    private function encodeOptions() : string
    {
        return ($this->getDataTransfer()->getHttpRequest()->has(Override::PRETTY_PRINT)
            && $this->getDataTransfer()->getHttpRequest()->get(Override::PRETTY_PRINT) == 1)
                ? JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT
                : JSON_UNESCAPED_UNICODE;
    }
}
