<?php

namespace Startcode\Runner\Presenter\View\Twig;

class Template
{

    private string $templatesPath;

    /**
     *
     * @var \Twig_Environment
     */
    private $templateEngine;

    public function __construct(string $templatesPath)
    {
        $this->templatesPath = $templatesPath;
    }

    /**
     * @throws \Exception
     * @return string
     */
    public function render($data, $templateName) : string
    {
        if (!$this->getFilesystemLoader()->exists($templateName)) {
            throw new \Exception('Twig template does not exist: ' . $templateName, 500);
        }

        return $this->getTemplateEngine()
            ->loadTemplate($templateName)
            ->render($data);
    }

    private function getFilesystemLoader() : \Twig_Loader_Filesystem
    {
        return new \Twig_Loader_Filesystem($this->templatesPath);
    }

    private function getTemplateEngine() : \Twig_Environment
    {
        if(!$this->templateEngine instanceof \Twig_Environment) {
            if(is_callable(['\App\DI', 'templateEngine'])) {
                $this->templateEngine = \App\DI::templateEngine();
                if(!$this->templateEngine instanceof \Twig_Environment) {
                    throw new \Exception("Template engine class is invalid");
                }
            } else {
                $this->templateEngine = new \Twig_Environment($this->getFilesystemLoader(), [
                    'cache'       => realpath(PATH_CACHE),
                    'auto_reload' => true,
                    'debug'       => true,
                ]);
                $this->templateEngine->addExtension(new \Twig_Extensions_Extension_I18n());
                $this->templateEngine->addExtension(new \Twig_Extension_Debug());
            }
        }

        return $this->templateEngine;
    }
}
