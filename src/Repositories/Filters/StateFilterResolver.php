<?php

namespace Gingerminds\LaravelCore\Repositories\Filters;

use Spatie\ModelStates\State;
use Throwable;

/**
 * Resolves `select-state` filter values against a Spatie model-states cast.
 *
 * Extracted out of AbstractRepository (which was hitting Sonar's max-methods-per-class
 * threshold) since this resolution logic doesn't depend on any repository state.
 */
class StateFilterResolver
{
    /**
     * Resolves a filter value (state short name, morph class, or `code()`/`label()`
     * translation key) into the fully-qualified state class name stored in the
     * database. Falls back to the raw value untouched if it can't be resolved,
     * so an already-qualified class name (or an unrecognized value) still works.
     */
    public function resolve(string $modelClass, string $property, ?string $value = null): ?string
    {
        if (null === $value || class_exists($value)) {
            return $value;
        }

        return $this->matchStateClass($modelClass, $property, $value) ?? $value;
    }

    /**
     * Looks up the fully-qualified state class matching $value among every state
     * registered on $property's base state class, or null if none matches
     * (unresolvable model/property/cast, or no state matched $value).
     */
    private function matchStateClass(string $modelClass, string $property, string $value): ?string
    {
        $stateBaseClass = $this->resolveStateBaseClass($modelClass, $property);

        if (null === $stateBaseClass) {
            return null;
        }

        foreach ($this->safeStateList($stateBaseClass) as $stateClass) {
            if (class_exists($stateClass) && $this->stateMatchesValue($stateClass, $value)) {
                return $stateClass;
            }
        }

        return null;
    }

    /**
     * Resolves the base Spatie state class registered as a cast for $property on
     * $modelClass, or null if it can't be resolved (unknown model, no `getCasts()`,
     * no cast for $property, or the cast isn't a state class).
     */
    private function resolveStateBaseClass(string $modelClass, string $property): ?string
    {
        if (!class_exists($modelClass)) {
            return null;
        }

        $model          = new $modelClass();
        $stateBaseClass = method_exists($model, 'getCasts') ? ($model->getCasts()[$property] ?? null) : null;

        if (!is_string($stateBaseClass) || !is_a($stateBaseClass, State::class, true)) {
            return null;
        }

        return $stateBaseClass;
    }

    /**
     * @return iterable<class-string>
     */
    private function safeStateList(string $stateBaseClass): iterable
    {
        try {
            return $stateBaseClass::all();
        } catch (Throwable) {
            return [];
        }
    }

    /**
     * Whether $value matches $stateClass's `code()`, `label()`, morph class, or
     * short class name, case-insensitively.
     */
    private function stateMatchesValue(string $stateClass, string $value): bool
    {
        $morph = $stateClass::getMorphClass();

        return (method_exists($stateClass, 'code') && strcasecmp((string) $stateClass::code(), $value) === 0)
            || (method_exists($stateClass, 'label') && strcasecmp((string) $stateClass::label(), $value) === 0)
            || (is_string($morph) && strcasecmp($morph, $value) === 0)
            || strcasecmp(class_basename($stateClass), $value) === 0;
    }
}
