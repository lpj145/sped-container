<?php
namespace SpedTransform\Support;

/**
 * Interface SpedAttribute
 * @package SpedTransform\Support
 * @method __invoke(...$args)
 */
interface SpedAttribute
{
    public function toStd(): \stdClass;

    public function toArray(): array;

    public function isExecuted(): bool;
}
