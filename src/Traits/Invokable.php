<?php

declare(strict_types=1);

namespace AlexKassel\Contracts\Traits;

trait Invokable
{
    public function __invoke(?string $method = null, ?array $arguments = null): mixed
    {
        return $this->__call($method ?? 'handle', $arguments ?? $_REQUEST);
    }

    public function __call(string $name, array $arguments): mixed
    {
        $reflectionClass = new \ReflectionClass($this);
        if ($reflectionMethod = $reflectionClass->getMethod($name)) {
            if (! $reflectionMethod->isPrivate()) {
                return $this->$name(...$arguments);
            }
        }

        throw new \InvalidArgumentException(sprintf('Method %s::%s() does not exist', __CLASS__, $name));
    }

    public static function __callStatic(string $name, array $arguments): mixed
    {
        return (new static())($name, $arguments ?: null);
    }

    protected function handle(...$arguments): mixed
    {
        throw new \InvalidArgumentException(sprintf('Default Method handle() is not defined in %s', __CLASS__));
    }
}
