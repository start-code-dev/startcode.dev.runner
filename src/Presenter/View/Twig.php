<?php

namespace Startcode\Runner\Presenter\View;

use Startcode\Runner\Presenter\DataTransfer;
use Startcode\Runner\Presenter\View\Twig\Template;
use Startcode\CleanCore\Constants\{Http, Template as TemplateConst};

class Twig extends ViewAbstract implements ViewInterface
{

    private ?string $layoutName = null;

    private string $templatesPath;

    public function __construct(array $data, DataTransfer $dataTransfer)
    {
        parent::__construct($data, $dataTransfer);
        $this->templatesPath  = realpath(PATH_APP . '/templates/' . strtolower($this->getDataTransfer()->getRequest()->getModule()));
        $this->layoutName     = $this->getDataTransfer()->getResponse()->getResponseObjectPart(TemplateConst::LAYOUT);
    }

    /**
     * @throws \Exception
     */
    public function renderBody() : string
    {
        return $this->hasLayout()
            ? $this->renderContentWithLayout()
            : $this->renderOnlyContent();
    }

    private function hasLayout() : bool
    {
        return !empty($this->layoutName);
    }

    private function renderContentWithLayout() : string
    {
        return (new Template($this->templatesPath))->render($this->getLayoutData(), $this->getLayoutFilename());
    }

    private function renderOnlyContent() : string
    {
        return (new Template($this->templatesPath))->render($this->getData(), $this->getContentFilename());
    }

    private function getContentFilename() : string
    {
        $templateName = $this->getDataTransfer()->getResponse()->getResponseObjectPart(TemplateConst::TEMPLATE);
        return ($templateName ?? $this->buildTemplateNameFromDataTransfer())
            . TemplateConst::EXTENSION_TWIG;
    }

    private function hasErrorsInResponse() : bool
    {
        return $this->getDataTransfer()->getResponse()->getHttpResponseCode() < Http::CODE_200
            || $this->getDataTransfer()->getResponse()->getHttpResponseCode() >= Http::CODE_300;
    }

    private function buildTemplateNameFromDataTransfer() : string
    {
        $parts = $this->hasErrorsInResponse()
            ? ['error', 'index']
            : [$this->getDataTransfer()->getRequest()->getResourceName(), $this->getDataTransfer()->getRequest()->getMethod()];
        return strtolower(implode('/', $parts));
    }

    private function getLayoutData() : array
    {
        return $this->getData()
            + [TemplateConst::CONTENT => $this->renderOnlyContent()];
    }

    private function getLayoutFilename() : string
    {
        return $this->layoutName . TemplateConst::EXTENSION_TWIG;
    }
}
