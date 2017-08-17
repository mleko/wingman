<?php


namespace Mleko\Wingman;


class Rule
{
    /** @var string */
    private $test;
    /** @var bool */
    private $sortChildren;
    /** @var Rules|null */
    private $childRules;

    /**
     * Rule constructor.
     * @param string $test
     * @param bool $sortChildren
     * @param Rules $childRules
     */
    public function __construct(string $test, bool $sortChildren = false, ?Rules $childRules = null)
    {
        $this->test = "!^$test$!";
        $this->sortChildren = $sortChildren;
        $this->childRules = $childRules;
    }

    /**
     * @return bool
     */
    public function isSortChildren(): bool
    {
        return $this->sortChildren;
    }

    /**
     * @return Rules
     */
    public function getChildRules(): ?Rules
    {
        return $this->childRules;
    }

    public function isMatch(string $key): bool
    {
        return preg_match($this->test, $key);
    }
}
