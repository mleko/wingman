<?php
/**
 * Wingman
 *
 * @link      http://github.com/mleko/wingman
 * @copyright Copyright (c) 2017 Daniel Król
 * @license   MIT
 */

namespace Mleko\Wingman;


class Formatter
{
    public static function format($element, Rules $rules)
    {
        $element = static::sort($element, new Comparator($rules));
        return static::formatChildren($element, $rules);
    }

    public static function sort($item, callable $comparator)
    {
        if (is_object($item)) {
            $copy = (array)$item;
            uksort($copy, $comparator);
            return (object)$copy;
        }
        return $item;
    }

    /**
     * @param array|object $element
     * @param Rules $rules
     * @return array|object
     */
    private static function formatChildren($element, Rules $rules)
    {
        foreach ($element as $key => &$value) {
            if (!is_object($value)) {
                continue;
            }
            $index = $rules->findMatching($key);
            if (null === $index) {
                continue;
            }
            $rule = $rules->getRule($index);
            if (!$rule->sortChildren()) {
                continue;
            }
            $value = static::format($value, $rule->getChildRules());
        }
        return $element;
    }
}
