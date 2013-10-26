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
use Splot\TwigModule\View\View;

class SplotTwigModule extends AbstractModule
{

    /**
     * Twig instance.
     * 
     * @var Twig_Environment
     */
    private $_twig;

    /**
     * Boots the module.
     */
    public function boot() {
        $loader = new TemplateLoader($this->container->get('resource_finder'));
        $this->_twig = $twig = new Twig_Environment($loader, array(
            'cache' => $this->container->getParameter('cache_dir') .'twig/',
            'auto_reload' => $this->getApplication()->isDevEnv()
        ));

        // define Twig as a service.
        $this->container->set('twig', function($c) use ($twig) {
            return $twig;
        }, true);

        View::setTwig($twig);

        // define templating service
        $this->container->set('templating', function($c) {
            return new TwigEngine($c->get('twig'));
        }, true, true);

        // register Twig extensions
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
            } elseif (is_object($response) && $response instanceof View) {
                $controllerResponse->setResponse($response->render());
            }
        });
    }

    /*****************************************************
     * SETTERS AND GETTERS
     *****************************************************/
    /**
     * Returns Twig.
     * 
     * @return \Twig_Environment
     */
    public function getTwig() {
        return $this->_twig;
    }

}