<?php
/**
 * Twig databridge extension.
 * 
 * @package SplotTwigModule
 * @subpackage Twig
 * @author Michał Dudek <michal@michaldudek.pl>
 * 
 * @copyright Copyright (c) 2013, Michał Dudek
 * @license MIT
 */
namespace Splot\TwigModule\Twig\Extension;

use MD\Foundation\Utils\StringUtils;

use Splot\Framework\DataBridge\DataBridge;

class DataBridgeExtension extends \Twig_Extension
{

    /**
     * DataBridge.
     * 
     * @var DataBridge
     */
    protected $databridge;

    /**
     * Placeholder string.
     * 
     * @var string
     */
    protected $placeholder;

    /**
     * Constructor.
     * 
     * @param DataBridge $databridge Splot DataBridge.
     */
    public function __construct(DataBridge $databridge) {
        $this->databridge = $databridge;
        $this->placeholder = '<!-- SPLOT_DATABRIDGE_PLACEHOLDER::'. StringUtils::random() .'::'. time() .' -->';
    }

    /**
     * Returns Twig global functions registered by this extension.
     * 
     * @return array
     */
    public function getFunctions() {
        return array(
            new \Twig_SimpleFunction('databridge', array($this, 'getPlaceholder'), array('is_safe' => array('html')))
        );
    }

    /**
     * Returns the databridge placeholder.
     * 
     * @return string
     */
    public function getPlaceholder() {
        return $this->placeholder;
    }

    /**
     * Returns the code for injecting the databridge.
     * 
     * @param string $var [optional] Variable name under which the databridge will be registered in JavaScript. Default: 'DataBridge'.
     * @return string
     */
    public function getCode($var = 'DataBridge') {
        return '<script type="text/javascript" data-databridge>var '. $var .'=(function(){var d='. $this->databridge->toJson() .';return function(k){return d[k];}})();</script>';
    }

    /**
     * Returns the name of this extension.
     * 
     * @return string
     */
    public function getName() {
        return 'splot_databridge';
    }

}