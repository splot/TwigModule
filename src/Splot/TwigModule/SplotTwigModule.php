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

use Twig_Environment;

use Splot\Framework\Framework;
use Splot\Framework\Modules\AbstractModule;
use Splot\Framework\Events\ControllerDidRespond;
use Splot\Framework\Events\WillSendResponse;

use Splot\TwigModule\Templating\TwigEngine;
use Splot\TwigModule\Twig\Extension\ConfigExtension;
use Splot\TwigModule\Twig\Extension\RoutesExtension;
use Splot\TwigModule\Twig\TemplateLoader;

class SplotTwigModule extends AbstractModule
{

    public function configure() {
        $loader = new TemplateLoader($this->container->get('resource_finder'));
        $twig = new Twig_Environment($loader, array(
            'cache' => $this->container->getParameter('cache_dir') .'twig/',
            'auto_reload' => $this->container->getParameter('debug')
        ));

        // define Twig as services
        $this->container->set('twig', $twig);

        // define templating service
        $this->container->set('templating', function($c) {
            return new TwigEngine($c->get('twig'));
        });

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

    public function run() {
        $twig = $this->container->get('twig');

        // register Twig extensions
        $twig->addExtension(new ConfigExtension($this->container->get('config')));
        $twig->addExtension(new RoutesExtension($this->container->get('application'), $this->container->get('router')));
    }

}