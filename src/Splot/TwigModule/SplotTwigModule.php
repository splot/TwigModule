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
use Splot\TwigModule\Twig\Extension\AppExtension;
use Splot\TwigModule\Twig\Extension\ConfigExtension;
use Splot\TwigModule\Twig\Extension\RoutesExtension;
use Splot\TwigModule\Twig\TemplateLoader;

class SplotTwigModule extends AbstractModule
{

    public function configure() {
        $this->container->set('twig.template_loader', function($c) {
            return new TemplateLoader($c->get('resource_finder'));
        });

        $this->container->set('twig', function($c) {
            return new Twig_Environment($c->get('twig.template_loader'), array(
                'cache' => $c->getParameter('cache_dir') .'twig/',
                'auto_reload' => $c->getParameter('debug')
            ));
        });

        $this->container->set('templating', function($c) {
            return new TwigEngine($c->get('twig'));
        });
    }

    public function run() {
        $twig = $this->container->get('twig');

        // register Twig extensions
        $twig->addExtension(new AppExtension($this->container->get('application')));
        $twig->addExtension(new ConfigExtension($this->container->get('config')));
        $twig->addExtension(new RoutesExtension($this->container->get('application'), $this->container->get('router')));

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