<?php


namespace Mleko\Wingman;


class Rules
{
    /** @var Rule[] */
    private $rules;

    /**
     * Rules constructor.
     * @param Rule[] $rules
     */
    public function __construct(array $rules)
    {
        $this->rules = $rules;
    }

    public function findMatching(string $key): ?int
    {
        foreach ($this->rules as $index => $rule) {
            if ($rule->isMatch($key)) {
                return $index;
            }
        }
        return null;
    }

    public function getRule(int $index): ?Rule
    {
        return isset($this->rules[$index]) ? $this->rules[$index] : null;
    }
}
