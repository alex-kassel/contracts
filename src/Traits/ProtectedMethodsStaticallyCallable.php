<?php

namespace AlexKassel\Contracts\Traits;

trait ProtectedMethodsStaticallyCallable
{
    /**
     * @throws \ReflectionException
     */
    public static function __callStatic(string $method, array $args): mixed
    {
        $reflectionClass = new \ReflectionClass(static::class);
        if ($reflectionMethod = $reflectionClass->getMethod($method)) {
            if ($reflectionMethod->isProtected()) {
                return (new static)->{$method}(...$args);
            }
        }

        throw new \BadMethodCallException(sprintf(
            'Method %s::%s does not exist.', static::class, $method
        ));
    }
}
