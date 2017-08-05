<?php

namespace Zeeml\DataSet\Core;

/**
 * Class CleanPolicy
 * Each CleanPolicy must be a callable
 * the callable policy must return true if policy is applied, if it returns false
 */
class CleanPolicy
{
    const AVG = CleanPolicy::class . '::AVG';
    const MOST_COMMON = CleanPolicy::class . '::MOST_COMMON';
    /**
     * No policy : no matter the value of the dimension or the output, it will be kept as is
     * @return callable
     */
    public static function none(): callable
    {
        return function () : bool {
            return true;
        };
    }

    /**
     * Skip policy : ignore the row if the corresponding dimension or output are empty
     * @return callable
     */
    public static function skip(): callable
    {
        return function (& $val) : bool {
            if (empty($val)) {
                return false;
            }

            return true;
        };
    }

    /**
     * replaceWith policy : if empty the value of the dimension or the output will be replaced by the given replacement
     * @param $replacement
     * @return callable
     */
    public static function replaceWith($replacement): callable
    {
        return function (& $val) use ($replacement) : bool{
            if (empty($val)) {
                $val = $replacement;
            }

            return true;
        };
    }

    /**
     * replaceWithAvg policy : if empty the value of the dimension or the output will be replaced by the average value after other cleaners are run
     * (
     * @return callable
     */
    public static function replaceWithAvg(): callable
    {
        return function (&$val) : bool {
            if (empty($val)) {
                $val = self::AVG;
            }

            return true;
        };
    }

    /**
     * replaceWithMostCommon policy : if empty the value of the dimension or the output will be replaced by the most common value (the one that occurs the most)
     * (
     * @return callable
     */
    public static function replaceWithMostCommon(): callable
    {
        return function (&$val) : bool {
            if (empty($val)) {
                $val = self::MOST_COMMON;
            }

            return true;
        };
    }

    /**
     * Custom Policy : define your own policy
     * @param callable $function
     * @return callable
     */
    public static function custom(callable $function): callable
    {
        return $function;
    }
}


