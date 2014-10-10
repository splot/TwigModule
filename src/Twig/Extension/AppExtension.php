<?php
/**
 * Twig app extension exposes several app variables in the templates.
 * 
 * @package SplotTwigModule
 * @subpackage Twig
 * @author Michał Dudek <michal@michaldudek.pl>
 * 
 * @copyright Copyright (c) 2013, Michał Dudek
 * @license MIT
 */
namespace Splot\TwigModule\Twig\Extension;

use Splot\Framework\Application\AbstractApplication;

class AppExtension extends \Twig_Extension
{

    /**
     * Application.
     * 
     * @var AbstractApplication
     */
    protected $application;

    /**
     * Constructor.
     * 
     * @param AbstractApplication $application Splot Application.
     */
    public function __construct(AbstractApplication $application) {
        $this->application = $application;
    }

    /**
     * Returns Twig global functions registered by this extension.
     * 
     * @return array
     */
    public function getGlobals() {
        return array(
            'app' => array(
                'name' => $this->application->getName(),
                'version' => $this->application->getVersion(),
                'env' => $this->application->getEnv(),
                'debug' => $this->application->isDebug()
            )
        );
    }

    /**
     * Returns the name of this extension.
     * 
     * @return string
     */
    public function getName() {
        return 'splot_app';
    }

}
