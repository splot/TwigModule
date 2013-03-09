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

use Splot\Framework\Framework;
use Splot\Framework\Modules\AbstractModule;
use Splot\Framework\Events\DidExecuteRoute;

use Splot\TwigModule\Twig\Extension\RoutesExtension;
use Splot\TwigModule\Twig\TemplateLoader;
use Splot\TwigModule\View\View;

class SplotTwigModule extends AbstractModule
{

    /**
     * Twig instance.
     * 
     * @var \Twig_Environment
     */
    private $_twig;

    /**
     * Boots the module.
     */
    public function boot() {
        $loader = new TemplateLoader($this->container->get('resource_finder'));
        $this->_twig = $twig = new \Twig_Environment($loader, array(
            'cache' => Framework::getFramework()->getCacheDir() .'twig/',
            'auto_reload' => $this->getApplication()->isDevEnv()
        ));

        // define Twig as a service.
        $this->container->set('twig', function($c) use ($twig) {
            return $twig;
        }, true);

        View::setTwig($twig);

        // register Twig extension
        $twig->addExtension(new RoutesExtension($this->container->get('router')));

        /*
         * REGISTER LISTENERS
         */
        $this->container->get('event_manager')->subscribe(DidExecuteRoute::getName(), function($event) use ($twig) {
            $route = $event->getRouteMeta();
            $routeResponse = $event->getRouteResponse();
            $request = $event->getRequest();

            $response = $routeResponse->getResponse();

            if (is_array($response)) {
                $routeName = $route->getName();
                $routeMethodName = $route->getRouteMethodForHttpMethod($request->getMethod());

                $templateName = $routeName .':'. $routeMethodName .'.html.twig';

                $view = $twig->render($templateName, $response);
                $routeResponse->setResponse($view);
            } elseif (is_object($response) && $response instanceof View) {
                $routeResponse->setResponse($response->render());
            }
        });
    }

    /*
     * SETTERS AND GETTERS
     */
    /**
     * Returns Twig.
     * 
     * @return \Twig_Environment
     */
    public function getTwig() {
        return $this->_twig;
    }

}