<?php
namespace Splot\TwigModule\Tests\Twig\Extension;

use Splot\TwigModule\Twig\Extension\RoutesExtension;

class RoutesExtensionTest extends \PHPUnit_Framework_TestCase
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

        $routesExtension = new RoutesExtension($application, $router);

        $this->assertEquals('splot_routes', $routesExtension->getName());

        $functions = $routesExtension->getFunctions();
        $this->assertInternalType('array', $functions);

        // check if all functions are returned
        $functionsMap = array();
        foreach($functions as $fn) {
            $functionsMap[$fn->getName()] = $fn;
        }

        $this->assertArrayHasKey('url', $functionsMap);
        $this->assertEquals(array($routesExtension, 'generateUrl'), $functionsMap['url']->getCallable());
        $this->assertArrayHasKey('expose_url', $functionsMap);
        $this->assertEquals(array($routesExtension, 'exposeUrl'), $functionsMap['expose_url']->getCallable());
        $this->assertArrayHasKey('render', $functionsMap);
        $this->assertEquals(array($routesExtension, 'render'), $functionsMap['render']->getCallable());

        $this->assertEquals('/index/1', $routesExtension->generateUrl('index', array('id' => 1), false));
        $this->assertEquals('/index/{id}', $routesExtension->exposeUrl('index.exposed'));
        $this->assertEquals('rendered my controller', $routesExtension->render('TestController', array('id' => 1)));
    }

}