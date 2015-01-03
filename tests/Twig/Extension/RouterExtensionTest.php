<?php
namespace Splot\TwigModule\Tests\Twig\Extension;

use Splot\TwigModule\Twig\Extension\RouterExtension;

class RouterExtensionTest extends \PHPUnit_Framework_TestCase
{

    public function testExtension() {
        $response = $this->getMock('Splot\Framework\HTTP\Response');
        $response->expects($this->once())
            ->method('getContent')
            ->will($this->returnValue('rendered my controller'));

        $application = $this->getMockForAbstractClass('Splot\Framework\Application\AbstractApplication', array(), '', true, true, true, array('render'));
        $application->expects($this->once())
            ->method('render')
            ->with($this->equalTo('TestController'), $this->equalTo(array('id' => 1)))
            ->will($this->returnValue($response));

        $router = $this->getMockBuilder('Splot\Framework\Routes\Router')
            ->disableOriginalConstructor()
            ->getMock();
        $router->expects($this->once())
            ->method('generate')
            ->with($this->equalTo('index'), $this->equalTo(array('id' => 1)), $this->equalTo(false))
            ->will($this->returnValue('/index/1'));
        $router->expects($this->once())
            ->method('expose')
            ->with($this->equalTo('index.exposed'))
            ->will($this->returnValue('/index/{id}'));
        $router->expects($this->once())
            ->method('getProtocol')
            ->will($this->returnValue('http://'));
        $router->expects($this->once())
            ->method('getHost')
            ->will($this->returnValue('www.example.com'));
        $router->expects($this->once())
            ->method('getPort')
            ->will($this->returnValue(80));
        $router->expects($this->once())
            ->method('getProtocolAndHost')
            ->will($this->returnValue('http://www.example.com'));

        $routerExtension = new RouterExtension($application, $router);

        $this->assertEquals('splot_router', $routerExtension->getName());

        $functions = $routerExtension->getFunctions();
        $this->assertInternalType('array', $functions);

        // check if all functions are returned
        $functionsMap = array();
        foreach($functions as $fn) {
            $functionsMap[$fn->getName()] = $fn;
        }

        $this->assertArrayHasKey('url', $functionsMap);
        $this->assertEquals(array($routerExtension, 'generateUrl'), $functionsMap['url']->getCallable());
        $this->assertArrayHasKey('expose_url', $functionsMap);
        $this->assertEquals(array($routerExtension, 'exposeUrl'), $functionsMap['expose_url']->getCallable());
        $this->assertArrayHasKey('render', $functionsMap);
        $this->assertEquals(array($routerExtension, 'render'), $functionsMap['render']->getCallable());

        $this->assertEquals('/index/1', $routerExtension->generateUrl('index', array('id' => 1), false));
        $this->assertEquals('/index/{id}', $routerExtension->exposeUrl('index.exposed'));
        $this->assertEquals('rendered my controller', $routerExtension->render('TestController', array('id' => 1)));

        $globals = $routerExtension->getGlobals();
        $this->assertInternalType('array', $globals);
        $this->assertArrayHasKey('router', $globals);
        $this->assertInternalType('array', $globals['router']);
        $this->assertArrayHasKey('protocol', $globals['router']);
        $this->assertEquals('http://', $globals['router']['protocol']);
        $this->assertArrayHasKey('host', $globals['router']);
        $this->assertEquals('www.example.com', $globals['router']['host']);
        $this->assertArrayHasKey('port', $globals['router']);
        $this->assertEquals(80, $globals['router']['port']);
        $this->assertArrayHasKey('base', $globals['router']);
        $this->assertEquals('http://www.example.com', $globals['router']['base']);
    }

}