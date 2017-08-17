<?php

namespace Mleko\Wingman;

use PHPUnit\Framework\TestCase;

class ComparatorTest extends TestCase
{

    /**
     * @param int $expected
     * @param string $keyA
     * @param string $keyB
     * @param Rules $rules
     * @dataProvider caseProvider
     */
    public function testCompare(int $expected, string $keyA, string $keyB, Rules $rules)
    {
        $comparator = new Comparator($rules);
        $actual = $comparator->compare($keyA, $keyB);
        $this->assertEquals($expected, 0 <=> $actual);
    }

    public function caseProvider()
    {
        return [
            [0, "a", "a", new Rules([])],
            [1, "a", "b", new Rules([])],
            [-1, "a", "b", new Rules([new Rule("b")])],
            [-1, "a", "b", new Rules([new Rule("b"), new Rule("a")])],
        ];
    }
}
