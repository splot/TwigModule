<?php
/**
 * Twig integration with Splot Framework.
 * 
 * @package SplotTwigModule
 * @author Michał Dudek <michal@michaldudek.pl>
 * 
 * @copyright Copyright (c) 2013, Michał Dudek
 * @license MIT
 */
namespace Splot\TwigModule;

use Splot\Framework\Modules\AbstractModule;
use Splot\Framework\Events\ControllerDidRespond;

class SplotTwigModule extends AbstractModule
{

    public function configure() {
        parent::configure();

        if ($this->container->getParameter('debug')) {
            $this->container->register('twig.extension.debug', array(
                'class' => 'Twig_Extension_Debug',
                'private' => true,
                'notify' => array(
                    array('@twig', 'addExtension', array('@'))
                )
            ));
        }
    }

    public function run() {
        $twig = $this->container->get('twig');

        /*
         * REGISTER LISTENERS
         */
        $this->container->get('event_manager')->subscribe(ControllerDidRespond::getName(), function($event) use ($twig) {
            $controllerResponse = $event->getControllerResponse();
            $response = $controllerResponse->getResponse();

            if (is_array($response)) {
                $templateName = $event->getControllerName() .':'. $event->getMethod() .'.html.twig';

                $view = $twig->render($templateName, $response);
                $controllerResponse->setResponse($view);
            }
        });
    }

}
