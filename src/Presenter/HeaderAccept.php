<?php

namespace Startcode\Runner\Presenter;

use Aura\Accept\Accept;
use Aura\Accept\AcceptFactory;
use Aura\Accept\Media\MediaValue;
use Startcode\CleanCore\Constants\Format;
use Startcode\CleanCore\Constants\HeaderAccept as HeaderAcceptConst;

class HeaderAccept
{

    /**
     * @var Accept
     */
    private $accept;

    /**
     * @var array
     */
    private $availableContentTypes;

    /**
     * @var MediaValue
     */
    private $media;


    public function __construct($availableContentTypes = null)
    {
        $server = $_SERVER;
        unset($server['REQUEST_URI']);

        $acceptFactory = new AcceptFactory($server);
        $this->accept = $acceptFactory->newInstance();
        $this->availableContentTypes = $availableContentTypes;
    }

    public function getFormat() : string
    {
        $this->media = $this->accept->negotiateMedia($this->available());
        return $this->hasDetected()
            ? $this->acceptMap()[$this->media->getValue()]
            : Format::JSON;
    }

    private function acceptMap() : array
    {
        return [
            HeaderAcceptConst::JSON => Format::JSON,
            HeaderAcceptConst::HTML => Format::TWIG,
        ];
    }

    private function available() : array
    {
        return $this->availableContentTypes
            ? $this->availableContentTypes
            : [
                HeaderAcceptConst::JSON,
                HeaderAcceptConst::HTML,
            ];
    }

    private function hasDetected() : bool
    {
        return $this->media instanceof MediaValue;
    }
}
