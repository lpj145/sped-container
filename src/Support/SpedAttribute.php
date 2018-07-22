<?php
namespace SpedTransform\Support;
use SpedTransform\SpedCollection;

/**
 * Interface SpedAttribute
 * @package SpedTransform\Support
 * @method __invoke(...$args)
 */
interface SpedAttribute
{
    public function getSpedCollection(): SpedCollection;

    public function isExecuted(): bool;
}
