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

class RoutesExtension extends \Twig_Extension
{

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
    public function __construct(Router $router) {
        $this->_router = $router;
    }

    /**
     * Returns Twig functions registered by this extension.
     * 
     * @return array
     */
    public function getFunctions() {
        return array(
            new \Twig_SimpleFunction('url', array($this, 'generateUrl'))
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
     * Returns the name of this extension.
     * 
     * @return string
     */
    public function getName() {
        return 'splot_routes';
    }

}