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
    public function toStd($key = null): \stdClass;

    public function toArray($key = null): array;

    public function isExecuted(): bool;
}
