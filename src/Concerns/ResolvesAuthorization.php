<?php

namespace Lorisleiva\Actions\Concerns;

use Illuminate\Support\Facades\Gate;
use Illuminate\Auth\Access\AuthorizationException;

trait ResolvesAuthorization
{
    protected function resolveAuthorization()
    {
        if (! $this->passesAuthorization()) {
            $this->failedAuthorization();
        }

        return $this;
    }

    public function passesAuthorization()
    {
        if (method_exists($this, 'authorize')) {
            $parameters = $this->resolveMethodDependencies($this, 'authorize');
            return $this->authorize(...$parameters);
        }

        return true;
    }
    
    protected function failedAuthorization()
    {
        throw new AuthorizationException('This action is unauthorized.');
    }

    protected function can($ability, $arguments = [])
    {
        return Gate::allows($ability, $arguments);
    }
}