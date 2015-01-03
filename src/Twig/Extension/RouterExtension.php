<?php
/**
 * Twig Router extension to allow generation of routes URL's based on their names
 * and also expose some router parameters.
 * 
 * @package SplotTwigModule
 * @subpackage Twig
 * @author Michał Dudek <michal@michaldudek.pl>
 * 
 * @copyright Copyright (c) 2014, Michał Dudek
 * @license MIT
 */
namespace Splot\TwigModule\Twig\Extension;

use Splot\Framework\Routes\Router;
use Splot\Framework\Application\AbstractApplication;

class RouterExtension extends \Twig_Extension
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
     * @param AbstractApplication $application Splot Application instance.
     * @param Router $router Splot Router.
     */
    public function __construct(AbstractApplication $application, Router $router) {
        $this->_application = $application;
        $this->_router = $router;
    }

    /**
     * Returns Twig global functions registered by this extension.
     * 
     * @return array
     */
    public function getGlobals() {
        return array(
            'router' => array(
                'protocol' => $this->_router->getProtocol(),
                'host' => $this->_router->getHost(),
                'port' => $this->_router->getPort(),
                'base' => $this->_router->getProtocolAndHost()
            )
        );
    }

    /**
     * Returns Twig functions registered by this extension.
     * 
     * @return array
     */
    public function getFunctions() {
        return array(
            new \Twig_SimpleFunction('url', array($this, 'generateUrl')),
            new \Twig_SimpleFunction('expose_url', array($this, 'exposeUrl')),
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
     * @param bool $includeHost [optional] Should host be included? Default: false.
     * @return string
     */
    public function generateUrl($name, array $params = array(), $includeHost = false) {
        return $this->_router->generate($name, $params, $includeHost);
    }

    /**
     * Exposes a route URL pattern.
     * 
     * @param string $name Name of the route.
     * @return string
     */
    public function exposeUrl($name) {
        return $this->_router->expose($name);
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
        return 'splot_router';
    }

}
