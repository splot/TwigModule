<?php
namespace Splot\TwigModule\Tests\Twig;

use MD\Foundation\Exceptions\InvalidArgumentException;
use Splot\Framework\Resources\Exceptions\ResourceNotFoundException;

use Splot\TwigModule\Twig\TemplateLoader;

class TemplateLoaderText extends \PHPUnit_Framework_TestCase
{

    public function testFindingTemplatesInViewsResources() {
        $finder = $this->getMockBuilder('Splot\Framework\Resources\Finder')
            ->disableOriginalConstructor()
            ->getMock();
        $finder->expects($this->once())
            ->method('find')
            ->with($this->equalTo('my_template'), $this->equalTo('views'))
            ->will($this->returnValue('./my_template'));

        $templateLoader = new TemplateLoader($finder);

        $this->assertEquals('./my_template', $templateLoader->findTemplate('my_template'));
    }

    public function testUsingCacheForFindingTemplates() {
        $finder = $this->getMockBuilder('Splot\Framework\Resources\Finder')
            ->disableOriginalConstructor()
            ->getMock();
        $finder->expects($this->once())
            ->method('find')
            ->with($this->equalTo('my_template'), $this->equalTo('views'))
            ->will($this->returnValue('./my_template'));

        $templateLoader = new TemplateLoader($finder);

        $this->assertEquals('./my_template', $templateLoader->findTemplate('my_template'));

        // call the 2nd time to retrieve it from internal memory cache (Finder->find() should not be called again)
        $this->assertEquals('./my_template', $templateLoader->findTemplate('my_template'));
    }

    public function testHandlingAlreadyResolvedPaths() {
        $finder = $this->getMockBuilder('Splot\Framework\Resources\Finder')
            ->disableOriginalConstructor()
            ->getMock();
        $finder->expects($this->once())
            ->method('find')
            ->with($this->equalTo(__FILE__), $this->equalTo('views'))
            ->will($this->throwException(new InvalidArgumentException('string', 'int')));

        $templateLoader = new TemplateLoader($finder);
        $this->assertEquals(__FILE__, $templateLoader->findTemplate(__FILE__));
    }

    /**
     * @expectedException \MD\Foundation\Exceptions\InvalidArgumentException
     */
    public function testNotHandlingInexistentResolvedPaths() {
        $finder = $this->getMockBuilder('Splot\Framework\Resources\Finder')
            ->disableOriginalConstructor()
            ->getMock();
        $finder->expects($this->once())
            ->method('find')
            ->with($this->equalTo(__FILE__.'.stub'), $this->equalTo('views'))
            ->will($this->throwException(new InvalidArgumentException('string', 'int')));

        $templateLoader = new TemplateLoader($finder);
        $templateLoader->findTemplate(__FILE__.'.stub');
    }

    /**
     * @expectedException \Splot\Framework\Resources\Exceptions\ResourceNotFoundException
     */
    public function testNotFindingTemplates() {
        $finder = $this->getMockBuilder('Splot\Framework\Resources\Finder')
            ->disableOriginalConstructor()
            ->getMock();
        $finder->expects($this->once())
            ->method('find')
            ->with($this->equalTo('::index.html.twig'), $this->equalTo('views'))
            ->will($this->throwException(new ResourceNotFoundException()));

        $templateLoader = new TemplateLoader($finder);
        $templateLoader->findTemplate('::index.html.twig');
    }

}