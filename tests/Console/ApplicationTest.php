<?php
/**
 * Wingman
 *
 * @link      http://github.com/mleko/wingman
 * @copyright Copyright (c) 2017 Daniel Król
 * @license   MIT
 */

namespace Mleko\Wingman\Console;


use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Tester\ApplicationTester;

class ApplicationTest extends TestCase
{
    /**
     * @var vfsStreamDirectory
     */
    private $root;

    /**
     * set up test environment
     */
    public function setUp()
    {
        $this->root = vfsStream::setup('testRoot');
    }

    public function testApplication()
    {
        $application = new Application();
        $application->setAutoExit(false);
        $tester = new ApplicationTester($application);

        $virtualFile = vfsStream::newFile("composer.json");
        $this->root->addChild($virtualFile);
        $virtualFile->setContent(file_get_contents(__DIR__ . "/../fixtures/composer.1.input.json"));

        $tester->run(["format", "path" => $virtualFile->url()]);

        $this->assertEquals("Formatting file: vfs://testRoot/composer.json\n", $tester->getDisplay());

        $this->assertEquals(file_get_contents(__DIR__ . "/../fixtures/composer.1.expected.json"), $virtualFile->getContent());
    }
}
