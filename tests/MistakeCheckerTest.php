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
use PHPUnit\Framework\TestCase;

class MistakeCheckerTest extends TestCase
{
    public function testChecker()
    {
        $output = new ArrayOutput();
        $composerJson = [
            "name" => "mleko/wingman",
            "bugs" => "https://github.com/mleko/wingman/issues",
            "tags" => [
                "composer.json",
                "sort",
                "format"
            ]
        ];

        $checker = new MistakeChecker();
        $checker->checkForMistakes($composerJson, $output);

        $message = implode("", $output->messages);
        $this->assertEquals(<<<EOM
Potential mistakes have been found
tags => keywords
bugs => support

EOM
            , $message);
    }

    public function testCheckerOnValidFile()
    {
        $output = new ArrayOutput();
        $composerJson = [
            "name" => "mleko/wingman",
            "keywords" => [
                "composer.json",
                "sort",
                "format"
            ]
        ];

        $checker = new MistakeChecker();
        $checker->checkForMistakes($composerJson, $output);
        $this->assertEmpty($output->messages);
    }
}
