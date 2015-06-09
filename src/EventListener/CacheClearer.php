<?php
/**
 * An event listener that will clear the Twig cache when a cache clear
 * event is sent.
 * 
 * @package SplotTwigModule
 * @author Michał Pałys-Dudek <michal@michaldudek.pl>
 * 
 * @copyright Copyright (c) 2015, Michał Dudek
 * @license MIT
 */
namespace Splot\TwigModule\EventListener;

use Twig_Environment;

use Symfony\Component\Filesystem\Filesystem;

use Splot\DevToolsModule\Events\ClearCache;

class CacheClearer
{ 

    /**
     * Twig instance for which to clear the cache.
     *
     * @var Twig_Environment
     */
    protected $twig;

    /**
     * Filesystem service.
     *
     * @var Filesystem
     */
    protected $filesystem;

    /**
     * Constructor.
     *
     * @param Twig_Environment $twig       Twig instance for which to clear the cache.
     * @param Filesystem       $filesystem Filesystem service.
     */
    public function __construct(Twig_Environment $twig, Filesystem $filesystem) {
        $this->twig = $twig;
        $this->filesystem = $filesystem;
    }

    /**
     * Clears the Twig cache.
     *
     * @param  ClearCache $event [optional] ClearCache event.
     */
    public function clearCache(ClearCache $event = null) {
        $cacheDir = rtrim($this->twig->getCache(), '/');
        $this->filesystem->remove($cacheDir);
    }
}
