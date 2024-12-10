<?php

namespace App\Infrastructure\HttpClient\Request;

use Symfony\Component\HttpFoundation\Request as HttpRequest;

abstract class AbstractRequest implements Request
{
    abstract public function getEndpoint(): string;

    public function getMethod(): string
    {
        return HttpRequest::METHOD_GET;
    }
}