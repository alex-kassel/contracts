<?php

declare(strict_types=1);

namespace AlexKassel\Contracts\Traits;

trait Invokable
{
    protected string $defaultMethod = 'handle';

    public function __invoke(?string $method = null, ?array $args = null): mixed
    {
        return $this->callMethod(
            $method ?? $this->defaultMethod,
            $args ?? []
        );
    }

    public function callMethod(string $method, array $args): mixed
    {
        return $this->{$method}(...$args);
    }
}
