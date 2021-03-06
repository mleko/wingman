<?php
/**
 * Wingman
 *
 * @link      http://github.com/mleko/wingman
 * @copyright Copyright (c) 2017 Daniel Król
 * @license   MIT
 */

namespace Mleko\Wingman;


class Comparator
{
    /** @var Rules */
    private $rules;

    public function __construct(Rules $rules)
    {
        $this->rules = $rules;
    }

    public function compare($keyA, $keyB): int
    {
        $indexA = $this->rules->findMatching($keyA);
        $indexB = $this->rules->findMatching($keyB);
        // both found
        if (null !== $indexA && null !== $indexB) {
            return $indexA - $indexB;
        }
        // none found
        if ($indexA === $indexB) {
            return strnatcmp($keyA, $keyB);
        }
        return null === $indexA ? 1 : -1;
    }

    public function __invoke($keyA, $keyB)
    {
        return $this->compare($keyA, $keyB);
    }
}
