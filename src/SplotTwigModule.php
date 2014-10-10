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

use Splot\Framework\Modules\AbstractModule;

class SplotTwigModule extends AbstractModule
{

    public function configure() {
        parent::configure();

        if ($this->container->getParameter('debug')) {
            $this->container->register('twig.extension.debug', array(
                'class' => 'Twig_Extension_Debug',
                'private' => true,
                'notify' => array(
                    array('@twig', 'addExtension', array('@'))
                )
            ));
        }
    }

}
