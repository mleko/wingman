<?php


namespace Mleko\Wingman;


class Comparator
{
    private $keys = [];

    /**
     * @param Rule[] $keys
     */
    public function __construct(array $keys)
    {
        $this->keys = $keys;
    }

    public function compare($keyA, $keyB): int
    {
        $indexA = $this->testKey($keyA, $this->keys);
        $indexB = $this->testKey($keyB, $this->keys);
        // both found
        if (null !== $indexA && null !== $indexB) {
            return $indexA - $indexB;
        }
        // none found
        if ($indexA === $indexB) {
            return strcmp($keyA, $keyB);
        }
        return null === $indexA ? 1 : -1;
    }

    public function __invoke($keyA, $keyB)
    {
        return $this->compare($keyA, $keyB);
    }

    /**
     * @param string $key
     * @param Rule[] $rules
     * @return int|null
     */
    private function testKey(string $key, $rules): ?int
    {
        foreach ($rules as $index => $rule) {
            if ($rule->isMatch($key)) {
                return $index;
            }
        }
        return null;
    }
}
