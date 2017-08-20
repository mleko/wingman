<?php
/**
 * Wingman
 *
 * @link      http://github.com/mleko/wingman
 * @copyright Copyright (c) 2017 Daniel Król
 * @license   MIT
 */

namespace Mleko\Wingman;


class Rule
{
    /** @var string */
    private $test;
    /** @var bool */
    private $sortChildren;
    /** @var Rules */
    private $childRules;

    /**
     * Rule constructor.
     * @param string $test
     * @param bool $sortChildren
     * @param Rules $childRules
     */
    public function __construct(string $test, bool $sortChildren = false, Rules $childRules = null)
    {
        $this->test = "!^$test$!";
        $this->sortChildren = $sortChildren;
        $this->childRules = $childRules ?: new Rules([]);
    }

    /**
     * @return bool
     */
    public function sortChildren(): bool
    {
        return $this->sortChildren;
    }

    /**
     * @return Rules
     */
    public function getChildRules(): Rules
    {
        return $this->childRules;
    }

    public function isMatch(string $key): bool
    {
        return preg_match($this->test, $key);
    }
}
