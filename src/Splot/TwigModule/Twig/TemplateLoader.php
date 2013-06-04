<?php
/**
 * Template loader from filesystem for Twig.
 * 
 * Makes it possible to use resource links as template names.
 * 
 * @package SplotTwigModule
 * @subpackage Twig
 * @author Michał Dudek <michal@michaldudek.pl>
 * 
 * @copyright Copyright (c) 2013, Michał Dudek
 * @license MIT
 */
namespace Splot\TwigModule\Twig;

use Splot\Framework\Resources\Finder;
use Splot\Framework\Resources\Exceptions\ResourceNotFoundException;

class TemplateLoader extends \Twig_Loader_Filesystem
{

    /**
     * Resource finder.
     * 
     * @var Finder
     */
    private $_finder;

    /**
     * Cache of template name - template path pairs.
     * 
     * @var array
     */
    private $_cache = array();

    /**
     * Constructor.
     * 
     * @param Finder $finder Resource finder.
     */
    public function __construct(Finder $finder) {
        parent::__construct(array());

        $this->_finder = $finder;
    }

    /**
     * Finds a template with the given name.
     * 
     * @param string $template Template name / location / resource link.
     * @return string Path to the template file.
     * 
     * @throws ResourceNotFoundException When the template could not be found.
     */
    public function findTemplate($template) {
        if (isset($this->_cache[$template])) {
            return $this->_cache[$template];
        }

        $templatePath = '';

        try {
            $templatePath = $this->_finder->find($template, 'views');
        } catch (ResourceNotFoundException $e) {
            try {
                $templatePath = parent::findTemplate($template);
            } catch(\Twig_Error_Loader $twigError) {
                throw new ResourceNotFoundException('Could not find template "'. $template .'".', 404, $e);
            }
        }

        $this->_cache[$template] = $templatePath;
        return $templatePath;
    }

}