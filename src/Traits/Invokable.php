<?php

declare(strict_types=1);

namespace AlexKassel\Contracts\Traits;

trait Invokable
{
    protected string $defaultMethod = 'handle';
    protected bool $globalsInsteadOfEmptyInvokeArgs = false;

    public function __invoke(?string $method = null, ?array $args = null): mixed
    {
        return $this->callMethod(
            $method ?? $this->defaultMethod,
            $args ?? $this->globalsInsteadOfEmptyInvokeArgs ? [...$_GET, ...$_POST] : []
        );
    }

    public static function __callStatic(string $method, array $args): mixed
    {
        return (new static())->callMethod($method, $args);
    }

    public function callMethod(string $method, array $args): mixed
    {
        $reflectionClass = new \ReflectionClass($this);
        if ($reflectionMethod = $reflectionClass->getMethod($method)) {
            if (! $reflectionMethod->isPrivate()) {
                return $this->{$method}(...$args);
            }
        }

        throw new \BadMethodCallException(sprintf(
            'Method %s::%s does not exist.', static::class, $method
        ));
    }
}
