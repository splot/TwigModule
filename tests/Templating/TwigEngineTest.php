<?php
namespace Splot\TwigModule\Tests\Templating;

use Splot\TwigModule\Templating\TwigEngine;

/**
 * @coversDefaultClass Splot\TwigModule\Templating\TwigEngine
 */
class TwigEngineTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers ::__construct()
     * @covers ::render()
     */
    public function testRenderingInTwig() {
        $templateName = 'my_template';
        $templateParams = array(
            'var1' => 'val',
            'lorem' => 'ipsum',
            'dolor' => true
        );
        $compiledTemplate = '<html><head><title>My Title</title></head><body></body></html>';

        $twig = $this->getMock('Twig_Environment');
        $twig->expects($this->once())
            ->method('render')
            ->with($this->equalTo($templateName), $this->equalTo($templateParams))
            ->will($this->returnValue($compiledTemplate));

        $twigEngine = new TwigEngine($twig);
        $this->assertInstanceOf('Splot\Framework\Templating\TemplatingEngineInterface', $twigEngine);
        $view = $twigEngine->render($templateName, $templateParams);
        $this->assertEquals($view, $compiledTemplate);
    }

}