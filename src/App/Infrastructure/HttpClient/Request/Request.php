<?php

namespace App\Infrastructure\HttpClient\Request;

interface Request
{
    public function getEndpoint(): string;

    public function getMethod(): string;
}