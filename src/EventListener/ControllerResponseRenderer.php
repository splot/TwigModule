<?php
/**
 * An event listener that will auto-render appropriate
 * template for a controller that returned an array
 * as a response.
 * 
 * @package SplotTwigModule
 * @author Michał Dudek <michal@michaldudek.pl>
 * 
 * @copyright Copyright (c) 2014, Michał Dudek
 * @license MIT
 */
namespace Splot\TwigModule\EventListener;

use Splot\Framework\Events\ControllerDidRespond;
use Splot\Framework\Templating\TemplatingEngineInterface;

class ControllerResponseRenderer
{

    /**
     * Templating engine.
     * 
     * @var TemplatingEngineInterface
     */
    protected $templating;

    /**
     * Constructor.
     * 
     * @param TemplatingEngineInterface $templating Templating engine.
     */
    public function __construct(TemplatingEngineInterface $templating) {
        $this->templating = $templating;
    }

    /**
     * If a controller responded with an array then try to render
     * a template based on the controller name, where the response
     * will be template parameters.
     * 
     * @param ControllerDidRespond $event Event triggered when controller responds.
     */
    public function onControllerResponse(ControllerDidRespond $event) {
        $controllerResponse = $event->getControllerResponse();
        $response = $controllerResponse->getResponse();

        if (is_array($response)) {
            $templateName = $event->getControllerName() .':'. $event->getMethod() .'.html.twig';
            $renderedResponse = $this->templating->render($templateName, $response);
            $controllerResponse->setResponse($renderedResponse);
        }
    }

}
