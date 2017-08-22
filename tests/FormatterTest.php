<?php
/**
 * Wingman
 *
 * @link      http://github.com/mleko/wingman
 * @copyright Copyright (c) 2017 Daniel Król
 * @license   MIT
 */

namespace Mleko\Wingman;

use PHPUnit\Framework\TestCase;

class FormatterTest extends TestCase
{
    public static function stdClass(array $data)
    {
        $stdClass = new \stdClass();
        foreach ($data as $key => $value) {
            $stdClass->{$key} = $value;
        }
        return $stdClass;
    }

    /**
     * @param $input
     * @param $comparator
     * @param $expected
     * @dataProvider sortDataProvider
     */
    public function testSort($input, $comparator, $expected)
    {
        $actual = Formatter::sort($input, $comparator);
        $this->assertEquals($expected, $actual);
        $actualKeyMap = (array_keys((array)$actual));
        $expectedKeyMap = (array_keys((array)$expected));
        $this->assertEquals($expectedKeyMap, $actualKeyMap);
    }

    public function sortDataProvider()
    {
        return [
            [self::stdClass(["b" => 1, "a" => 2, "c" => "c"]), "strcmp", self::stdClass(["a" => 2, "b" => 1, "c" => "c"])],
            [1, "strcmp", 1],
            [[1, 3, 2], "strcmp", [1, 3, 2]]
        ];
    }
}
