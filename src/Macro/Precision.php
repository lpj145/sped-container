<?php
namespace SpedTransform\Macro;

use SpedTransform\Support\Number;

trait Precision
{
    /**
     * @param $value
     * @return string
     */
    public function normalizeValue($value, $base)
    {
        return (Number::factory($value))->base($base);
    }

    /**
     * Apply precison numbers
     * @param $value
     * @return string
     */
    public function precision10($value)
    {
        return self::normalizeValue($value, 10);
    }

    /**
     * @param $value
     * @return string
     */
    public function precision3($value)
    {
        return self::normalizeValue($value, 3);
    }

    /**
     * Apply precison numbers
     * @param $value
     * @return string
     */
    public function precision4($value)
    {
        return self::normalizeValue($value, 4);
    }

    /**
     * Apply precison numbers
     * @param $value
     * @return string
     */
    public function precision2($value)
    {
        return self::normalizeValue($value, 2);
    }
}
