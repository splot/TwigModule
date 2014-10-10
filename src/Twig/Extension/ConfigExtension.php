<?php
/**
 * Twig config extension allows access to config values inside templates.
 * 
 * @package SplotTwigModule
 * @subpackage Twig
 * @author Michał Dudek <michal@michaldudek.pl>
 * 
 * @copyright Copyright (c) 2013, Michał Dudek
 * @license MIT
 */
namespace Splot\TwigModule\Twig\Extension;

use Splot\Framework\Config\Config;

class ConfigExtension extends \Twig_Extension
{

    /**
     * Config.
     * 
     * @var Config
     */
    protected $config;

    /**
     * Constructor.
     * 
     * @param Config $config Splot Config.
     */
    public function __construct(Config $config) {
        $this->config = $config;
    }

    /**
     * Returns Twig global functions registered by this extension.
     * 
     * @return array
     */
    public function getFunctions() {
        return array(
            'config' => new \Twig_Function_Method($this, 'get')
        );
    }

    /**
     * Returns config value at the given path.
     * 
     * @param string $path Config path.
     * @return mixed
     */
    public function get($path) {
        return $this->config->get($path);
    }

    /**
     * Returns the name of this extension.
     * 
     * @return string
     */
    public function getName() {
        return 'splot_config';
    }

}