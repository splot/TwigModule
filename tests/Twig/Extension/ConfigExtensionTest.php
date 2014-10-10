<?php
namespace Splot\TwigModule\Tests\Twig\Extension;

use Splot\TwigModule\Twig\Extension\ConfigExtension;

class ConfigExtensionTest extends \PHPUnit_Framework_TestCase
{

    public function testExtension() {
        $config = $this->getMockBuilder('Splot\Framework\Config\Config')
            ->disableOriginalConstructor()
            ->getMock();
        $config->expects($this->once())
            ->method('get')
            ->with($this->equalTo('my.config.option'))
            ->will($this->returnValue('lipsum'));

        $configExtension = new ConfigExtension($config);

        $this->assertEquals('splot_config', $configExtension->getName());

        $functions = $configExtension->getFunctions();
        $this->assertInternalType('array', $functions);
        $this->assertArrayHasKey('config', $functions);
        $this->assertInstanceOf('Twig_Function_Method', $functions['config']);

        $this->assertEquals('lipsum', $configExtension->get('my.config.option'));
    }

}