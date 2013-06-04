<?php
/**
 * Twig routes extension to allow generation of routes URL's based on their names.
 * 
 * @package SplotTwigModule
 * @subpackage Twig
 * @author Michał Dudek <michal@michaldudek.pl>
 * 
 * @copyright Copyright (c) 2013, Michał Dudek
 * @license MIT
 */
namespace Splot\TwigModule\Twig\Extension;

use Splot\Framework\Routes\Router;
use Splot\Framework\Application\AbstractApplication;

class RoutesExtension extends \Twig_Extension
{

    /**
     * Application instance.
     * 
     * @var AbstractApplication
     */
    protected $_application;

    /**
     * Splot Router.
     * 
     * @var Router
     */
    protected $_router;

    /**
     * Constructor.
     * 
     * @param Router $router Splot Router.
     */
    public function __construct(AbstractApplication $application, Router $router) {
        $this->_application = $application;
        $this->_router = $router;
    }

    /**
     * Returns Twig functions registered by this extension.
     * 
     * @return array
     */
    public function getFunctions() {
        return array(
            new \Twig_SimpleFunction('url', array($this, 'generateUrl')),
            new \Twig_SimpleFunction('render', array($this, 'render'), array(
                'is_safe' => array('html')
            ))
        );
    }

    /**
     * Generates a route URL for the given name and parameters.
     * 
     * @param string $name Name of the route.
     * @param array $params [optional] Route parameters.
     * @return string
     */
    public function generateUrl($name, array $params = array()) {
        return $this->_router->generate($name, $params);
    }

    /**
     * Renders the given controller's respone with the given arguments.
     * 
     * @param string $controller Name of the controller.
     * @param array $arguments [optional] Arguments for the controller.
     * @return string
     */
    public function render($controller, array $arguments = array()) {
        $response = $this->_application->render($controller, $arguments);
        return $response->getContent();
    }

    /**
     * Returns the name of this extension.
     * 
     * @return string
     */
    public function getName() {
        return 'splot_routes';
    }

}