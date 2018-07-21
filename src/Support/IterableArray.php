<?php
namespace SpedTransform\Support;


trait IterableArray
{
    /**
     * @param $items
     * @param callable $c
     * @param mixed ...$options
     */
    protected function each($items, callable $c, ...$options)
    {
        foreach ($items as $key => $item) {
            if (null === $options) {
                $c($key, $item);
                continue;
            }
            $c($key, $item, ...$options);
        }
    }
}
