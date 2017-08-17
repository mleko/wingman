<?php

namespace Mleko\Wingman;

use PHPUnit\Framework\TestCase;

class WingmanTest extends TestCase
{
    private static function stdClass($data)
    {
        $c = new \stdClass();
        foreach ($data as $key => $value) {
            $c->{$key} = $value;
        }
        return $c;
    }

    /**
     * @dataProvider caseProvider
     */
    public function testFormat($expected, $input)
    {
        $fifer = new Wingman();
        $actual = $fifer->format($input);
        $this->assertEquals($expected, $actual);
        $jsonOptions = JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE;
        $this->assertEquals(json_encode($expected, $jsonOptions), json_encode($actual, $jsonOptions));
    }

    public function caseProvider()
    {
        return
            [
                [
                    [
                        "name" => "mleko/wingman",
                        "license" => "MIT"
                    ],
                    [
                        "license" => "MIT",
                        "name" => "mleko/wingman"
                    ]
                ],
                [
                    [
                        "name" => "mleko/wingman",
                        "license" => "MIT",
                        "require" => self::stdClass([
                            "php" => "7.0",
                            "mleko/narrator" => "0.1",
                            "mleko/wingman" => "0.1"
                        ])
                    ],
                    [
                        "license" => "MIT",
                        "name" => "mleko/wingman",
                        "require" => self::stdClass([
                            "mleko/wingman" => "0.1",
                            "mleko/narrator" => "0.1",
                            "php" => "7.0"
                        ])
                    ]
                ]
            ];
    }
}
