<?php

namespace Mleko\Wingman;

use PHPUnit\Framework\TestCase;

class ComparatorTest extends TestCase
{

    /**
     * @param int $expected
     * @param string $keyA
     * @param string $keyB
     * @param string[] $keys
     * @dataProvider caseProvider
     */
    public function testCompare(int $expected, string $keyA, string $keyB, array $keys)
    {
        $comparator = new Comparator($keys);
        $actual = $comparator->compare($keyA, $keyB);
        $this->assertEquals($expected, 0 <=> $actual);
    }

    public function caseProvider()
    {
        return [
            [0, "a", "a", []],
            [1, "a", "b", []],
            [-1, "a", "b", ["b"]],
            [-1, "a", "b", ["b", "a"]],
        ];
    }
}
