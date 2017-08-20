<?php
/**
 * Wingman
 *
 * @link      http://github.com/mleko/wingman
 * @copyright Copyright (c) 2017 Daniel Król
 * @license   MIT
 */

namespace Mleko\Wingman;

use Mleko\Wingman\IO\ArrayOutput;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use PHPUnit\Framework\TestCase;

class WingmanTest extends TestCase
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

    /**
     * @dataProvider caseProvider
     */
    public function testFormat($expected, $input)
    {
        $wingman = new Wingman();
        $actual = $wingman->format($input);
        $this->assertEquals($expected, $actual);
        $jsonOptions = JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE;
        $this->assertEquals(json_encode($expected, $jsonOptions), json_encode($actual, $jsonOptions));
    }

    public function caseProvider()
    {
        $cases = [];
        $iterator = new \DirectoryIterator(__DIR__ . "/fixtures");
        $pattern = "!^composer\.(\d+)\.input\.json$!";
        foreach ($iterator as $item) {
            if ($item->isDot()) {
                continue;
            }
            if (!preg_match($pattern, $item->getFilename(), $matches)) {
                continue;
            }
            $cases[] = [
                json_decode(file_get_contents(__DIR__ . "/fixtures/composer." . $matches[1] . ".expected.json")),
                json_decode(file_get_contents(__DIR__ . "/fixtures/composer." . $matches[1] . ".input.json"))
            ];
        }
        return $cases;
    }

    public function testMissingFile()
    {
        $wingman = new Wingman();

        $virtualFile = vfsStream::newFile("composer.json");
        // don't add file to root
        $result = $wingman->formatFile($virtualFile->url(), $output = new ArrayOutput());

        $this->assertFalse($result);
        $this->assertCount(1, $output->messages);
        $this->assertEquals("composer.json is not readable/writable\n", $output->messages[0]);
    }

    public function testUnReadableFile()
    {
        $wingman = new Wingman();

        $virtualFile = vfsStream::newFile("testDir/composer.json", 0222);
        $this->root->addChild($virtualFile);
        $result = $wingman->formatFile($virtualFile->url(), $output = new ArrayOutput());

        $this->assertFalse($result);
        $this->assertCount(1, $output->messages);
        $this->assertEquals("composer.json is not readable/writable\n", $output->messages[0]);
    }

    public function testUnWritableFile()
    {
        $wingman = new Wingman();

        $virtualFile = vfsStream::newFile("testDir/composer.json", 0444);
        $this->root->addChild($virtualFile);
        $result = $wingman->formatFile($virtualFile->url(), $output = new ArrayOutput());

        $this->assertFalse($result);
        $this->assertCount(1, $output->messages);
        $this->assertEquals("composer.json is not readable/writable\n", $output->messages[0]);
    }

    public function testFormattingFile()
    {
        $wingman = new Wingman();

        $virtualFile = vfsStream::newFile("composer.json");
        $this->root->addChild($virtualFile);
        $virtualFile->setContent(file_get_contents(__DIR__ . "/fixtures/composer.1.input.json"));

        $result = $wingman->formatFile($virtualFile->url(), $output = new ArrayOutput());

        $this->assertTrue($result);
        $this->assertCount(1, $output->messages);
        $this->assertEquals("Formatting file: vfs://testRoot/composer.json\n", $output->messages[0]);

        $this->assertEquals(file_get_contents(__DIR__ . "/fixtures/composer.1.expected.json"), $virtualFile->getContent());
    }
}
