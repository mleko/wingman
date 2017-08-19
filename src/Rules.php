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

    /**
     * @param string $key
     * @return int|null
     */
    public function findMatching(string $key)
    {
        foreach ($this->rules as $index => $rule) {
            if ($rule->isMatch($key)) {
                return $index;
            }
        }
        return null;
    }

    /**
     * @param int $index
     * @return Rule|null
     */
    public function getRule(int $index)
    {
        return isset($this->rules[$index]) ? $this->rules[$index] : null;
    }
}
