<?php
namespace SpedTransform\Macro;

trait DateFormat
{
    public function formatUTC(string $data)
    {
        return (new \DateTime($data))->format(DATE_W3C);
    }
}
