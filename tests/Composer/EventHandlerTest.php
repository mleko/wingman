<?php
/**
 * Wingman
 *
 * @link      http://github.com/mleko/wingman
 * @copyright Copyright (c) 2017 Daniel Król
 * @license   MIT
 */

namespace Mleko\Wingman\Composer;

use Composer\Composer;
use Composer\Config;
use Composer\IO\BufferIO;
use Composer\Json\JsonFile;
use Composer\Script\Event;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use PHPUnit\Framework\TestCase;

class EventHandlerTest extends TestCase
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

    public function testFormat()
    {
        $virtualFile = vfsStream::newFile("composer.json");
        $this->root->addChild($virtualFile);
        $virtualFile->setContent(file_get_contents(__DIR__ . "/../fixtures/composer.1.input.json"));

        $composer = new Composer();
        $config = new Config();
        $config->setConfigSource(new Config\JsonConfigSource(new JsonFile($virtualFile->url())));
        $composer->setConfig($config);
        $event = new Event("syntetic", $composer, $io = new BufferIO());

        EventHandler::format($event);

        $this->assertEquals("Formatting file: vfs://testRoot/composer.json\n", $io->getOutput());

        $this->assertEquals(file_get_contents(__DIR__ . "/../fixtures/composer.1.expected.json"), $virtualFile->getContent());
    }
}
