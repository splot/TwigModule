<?php
namespace Splot\TwigModule\Tests\EventListener;

use Splot\TwigModule\EventListener\ControllerResponseRenderer;

class ControllerResponseRendererTest extends \PHPUnit_Framework_TestCase
{

    public function testRenderControllerResponse() {
        $controllerResponse = $this->getMockBuilder('Splot\Framework\Controller\ControllerResponse')
            ->disableOriginalConstructor()
            ->getMock();
        $controllerResponse->expects($this->atLeastOnce())
            ->method('getResponse')
            ->will($this->returnValue(array('lorem' => 'ipsum')));
        $controllerResponse->expects($this->once())
            ->method('setResponse')
            ->with($this->equalTo('rendered controller'));

        $templating = $this->getMock('Splot\Framework\Templating\TemplatingEngineInterface');
        $templating->expects($this->once())
            ->method('render')
            ->with($this->equalTo('TestController:index.html.twig'), $this->equalTo(array('lorem' => 'ipsum')))
            ->will($this->returnValue('rendered controller'));

        $renderer = new ControllerResponseRenderer($templating);

        $event = $this->getMockBuilder('Splot\Framework\Events\ControllerDidRespond')
            ->disableOriginalConstructor()
            ->getMock();
        $event->expects($this->atLeastOnce())
            ->method('getControllerResponse')
            ->will($this->returnValue($controllerResponse));
        $event->expects($this->atLeastOnce())
            ->method('getControllerName')
            ->will($this->returnValue('TestController'));
        $event->expects($this->atLeastOnce())
            ->method('getMethod')
            ->will($this->returnValue('index'));

        $renderer->onControllerResponse($event);
    }

    public function testNotRenderingWhenNotArray() {
        $controllerResponse = $this->getMockBuilder('Splot\Framework\Controller\ControllerResponse')
            ->disableOriginalConstructor()
            ->getMock();
        $controllerResponse->expects($this->atLeastOnce())
            ->method('getResponse')
            ->will($this->returnValue('already rendered'));
        $controllerResponse->expects($this->never())
            ->method('setResponse');

        $templating = $this->getMock('Splot\Framework\Templating\TemplatingEngineInterface');
        $templating->expects($this->never())
            ->method('render');

        $renderer = new ControllerResponseRenderer($templating);

        $event = $this->getMockBuilder('Splot\Framework\Events\ControllerDidRespond')
            ->disableOriginalConstructor()
            ->getMock();
        $event->expects($this->atLeastOnce())
            ->method('getControllerResponse')
            ->will($this->returnValue($controllerResponse));
            
        $renderer->onControllerResponse($event);
    }

}