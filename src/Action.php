<?php

namespace Lorisleiva\Actions;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

abstract class Action extends Controller
{
    use Concerns\DependencyResolver;
    use Concerns\HasAttributes;
    use Concerns\ValidatesAttributes;
    use Concerns\VerifyAuthorization;

    public function __construct(array $attributes = [])
    {
        $this->fill($attributes);
    }

    public function __invoke(Request $request)
    {
        return $this->runAsController($request);
    }

    public function runAsController(Request $request)
    {
        $this->fill($this->getAttributesFromRequest($request));

        $this->resolveAuthorization();
        $this->resolveValidation();
        $result = $this->resolveHandle();

        return method_exists($this, 'response') ? $this->response($result, $request) : $result;
    }

    public function runAsListener($event)
    {
        $this->fill($this->getAttributesFromEvent($event));

        return $this->resolveHandle();
    }

    public function run()
    {
        $this->resolveAuthorization();
        $this->resolveValidation();
        return $this->resolveHandle();
    }

    public function resolveHandle()
    {
        $parameters = $this->resolveMethodDependencies($this, 'handle');

        return $this->handle(...$parameters);
    }

    public function getAttributesFromRequest(Request $request)
    {
        return array_merge(
            $request->route()->parametersWithoutNulls(),
            $request->all()
        );
    }

    public function getAttributesFromEvent($event)
    {
        return [];
    }
}