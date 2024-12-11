<?php

namespace App\Infrastructure\Json;

class JsonDecoder
{
    public function decode(string $json): array
    {
        $value = \json_decode($json, true);
        if (\json_last_error()) {
            throw new \RuntimeException('JSON decoding error: ' . \json_last_error_msg());
        }

        return $value;
    }
}