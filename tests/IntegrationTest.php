<?php
namespace Splot\TwigModule\Tests;

use Splot\TwigModule\SplotTwigModule;

class IntegrationTest extends \Splot\Framework\Testing\ApplicationTestCase
{

    public function setUp() {
        parent::setUp();
        $this->_application->addTestModule(new SplotTwigModule());
    }

    public function testTwigRegistered() {
        $container = $this->_application->getContainer();

        $this->assertTrue($container->has('twig'));

        $twig = $container->get('twig');
        $this->assertInstanceOf('Twig_Environment', $twig);

        $this->assertEquals($container->getParameter('debug'), $twig->isDebug());
        $this->assertEquals($container->getParameter('debug'), $twig->isAutoReload());

        $this->assertTrue($container->has('templating'));
        $this->assertInstanceOf('Splot\TwigModule\Templating\TwigEngine', $container->get('templating'));
    }

    public function testTwigHasExtensions() {
        $twig = $this->_application->getContainer()->get('twig');

        // app
        $this->assertTrue($twig->hasExtension('splot_app'));
        $this->assertInstanceOf('Splot\TwigModule\Twig\Extension\AppExtension', $twig->getExtension('splot_app'));
        
        // config
        $this->assertTrue($twig->hasExtension('splot_config'));
        $this->assertInstanceOf('Splot\TwigModule\Twig\Extension\ConfigExtension', $twig->getExtension('splot_config'));
        
        // routes
        $this->assertTrue($twig->hasExtension('splot_routes'));
        $this->assertInstanceOf('Splot\TwigModule\Twig\Extension\RoutesExtension', $twig->getExtension('splot_routes'));
        
        // debug
        $debug = $this->_application->getContainer()->getParameter('debug');
        $this->assertEquals($debug, $twig->hasExtension('debug'));
    }

    public function testAutoRenderingArrayControllerResponse() {
        
    }

    /*
    public function testListenerIsExecuted() {
        $request = $this->getMock('Splot\Framework\HTTP\Request');
        $response = $this->getMock('Splot\Framework\HTTP\Response');
        $response->expects($this->atLeastOnce())
            ->method('alterPart');
            
        $this->_application->sendResponse($response, $request);
    }
    */

}