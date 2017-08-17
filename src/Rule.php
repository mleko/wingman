<?php


namespace Mleko\Wingman;


class Rule
{
    /** @var string */
    private $test;
    /** @var bool */
    private $sortChildren;
    /** @var Rule[] */
    private $childRules;

    /**
     * Rule constructor.
     * @param string $test
     * @param bool $sortChildren
     * @param Rule[] $childRules
     */
    public function __construct(string $test, bool $sortChildren = false, array $childRules = [])
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
     * @return Rule[]
     */
    public function getChildRules(): array
    {
        return $this->childRules;
    }

    public function isMatch(string $key): bool
    {
        return preg_match($this->test, $key);
    }
}
