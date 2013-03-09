<?php
/**
 * View class.
 * 
 * @package SplotTwigModule
 * @subpackage View
 * @author Michał Dudek <michal@michaldudek.pl>
 * 
 * @copyright Copyright (c) 2013, Michał Dudek
 * @license MIT
 */
namespace Splot\TwigModule\View;

class View
{

    /**
     * Template name.
     * 
     * @var string
     */
    private $_template;

    /**
     * Template variables.
     * 
     * @var array
     */
    private $_variables = array();

    /**
     * Twig templating engine.
     * 
     * @var \Twig_Environment
     */
    protected static $_twig;

    /**
     * Constructor.
     * 
     * @param string $template Template name.
     * @param array $variables [optional] Variables that will be available in the template.
     */
    public function __construct($template, array $variables = array()) {
        $this->setTemplate($template);
        $this->setVariables($variables);
    }

    /**
     * Renders and returns this view.
     * 
     * @return string Rendered view.
     */
    public function render() {
        return self::$_twig->render($this->getTemplate(), $this->getVariables());
    }

    /*
     * SETTERS AND GETTERS
     */
    /**
     * Sets reference to Twig templating engine.
     * 
     * @param \Twig_Environment $twig
     */
    public static function setTwig(\Twig_Environment $twig) {
        self::$_twig = $twig;
    }

    /**
     * Returns Twig templating engine.
     * 
     * @return \Twig_Environment
     */
    public static function getTwig() {
        return self::$_twig;
    }

    /**
     * Sets template name.
     * 
     * @param string $template Template name.
     */
    public function setTemplate($template) {
        $this->_template = $template;
    }

    /**
     * Returns the template name.
     * 
     * @return string
     */
    public function getTemplate() {
        return $this->_template;
    }

    /**
     * Sets variables that will be available in the template.
     * 
     * @param array $variables
     */
    public function setVariables(array $variables) {
        $this->_variables = $variables;
    }

    /**
     * Returns variables associated with this view, that are available in the template.
     * 
     * @return array
     */
    public function getVariables() {
        return $this->_variables;
    }

    /**
     * Sets a specific template variable.
     * 
     * @param string $name Variable name.
     * @param mixed $variable Variable value.
     */
    public function setVariable($name, $variable) {
        $this->_variables[$name] = $variable;
    }

    /**
     * Returns a specific template variable.
     * 
     * @param string $name Variable name.
     * @return mixed
     */
    public function getVariable($name) {
        return $this->_variables[$name];
    }

    /**
     * Unsets the specified variable.
     * 
     * @param string $name Variable name.
     */
    public function unsetVariable($name) {
        unset($this->_variables[$name]);
    }

    /**
     * Convert to string when casted.
     * 
     * @return string
     */
    public function __toString() {
        return $this->render();
    }

}