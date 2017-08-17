<?php

namespace Mleko\Wingman;

use PHPUnit\Framework\TestCase;

class WingmanTest extends TestCase
{

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
}
