<?php
namespace Splot\TwigModule\Tests\Twig\Extension;

use Splot\TwigModule\Twig\Extension\AppExtension;

class AppExtensionTest extends \PHPUnit_Framework_TestCase
{

    public function testExtension() {
        $application = $this->getMockForAbstractClass('Splot\Framework\Application\AbstractApplication', array(), '', true, true, true, array('getName', 'getVersion', 'getEnv', 'isDebug'));
        $application->expects($this->once())
            ->method('getName')
            ->will($this->returnValue('TwigApplication'));
        $application->expects($this->once())
            ->method('getVersion')
            ->will($this->returnValue('0.1'));
        $application->expects($this->once())
            ->method('getEnv')
            ->will($this->returnValue('test'));
        $application->expects($this->once())
            ->method('isDebug')
            ->will($this->returnValue(true));

        $appExtension = new AppExtension($application);

        $this->assertEquals('splot_app', $appExtension->getName());

        $this->assertEquals(array(
            'app' => array(
                'name' => 'TwigApplication',
                'version' => '0.1',
                'env' => 'test',
                'debug' => true
            )
        ), $appExtension->getGlobals());
    }

}