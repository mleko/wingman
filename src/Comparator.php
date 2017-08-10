<?php


namespace Mleko\Wingman;


class Comparator
{
    private $keys = [];

    /**
     * @param string[] $keys
     */
    public function __construct(array $keys)
    {
        $this->keys = $keys;
    }

    public function compare($keyA, $keyB): int
    {
        $indexA = array_search($keyA, $this->keys);
        $indexB = array_search($keyB, $this->keys);
        // both found
        if (false !== $indexA && false !== $indexB) {
            return $indexA - $indexB;
        }
        // none found
        if ($indexA === $indexB) {
            return strcmp($keyA, $keyB);
        }
        return false === $indexA ? 1 : -1;
    }

    public function __invoke($keyA, $keyB)
    {
        return $this->compare($keyA, $keyB);
    }
}
