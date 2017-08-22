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
use Symfony\Component\Console\Tester\CommandTester;

class RegisterCommandTest extends TestCase
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

    public function testRegisterCommand()
    {
        $virtualFile = vfsStream::newFile("composer.json");
        $this->root->addChild($virtualFile);
        $virtualFile->setContent(json_encode(["name" => "acme/test"]));

        $application = new Application();
        $registerCommand = $application->find("register");
        $commandTester = new CommandTester($registerCommand);

        $resultCode = $commandTester->execute([
            "command" => $registerCommand->getName(),
            "path" => $virtualFile->url()
        ]);

        $this->assertEquals(0, $resultCode);
        $contents = json_decode($virtualFile->getContent());
        $this->assertContains("Mleko\\Wingman\\Composer\\EventHandler::format", $contents->scripts->{"post-update-cmd"});
        $this->assertEquals("Register wingman in file: vfs://testRoot/composer.json\nWingman registered\nFormatting file: vfs://testRoot/composer.json\n", $commandTester->getDisplay());
    }
}
