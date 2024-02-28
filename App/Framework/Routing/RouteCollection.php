<?php

namespace App\Framework\Routing;

use App\Framework\Http\Request;

class RouteCollection
{
    /**
     * Routes of this application.
     *
     * @var Route[]
     */
    private array $routes = [];

    /**
     * Get all routes.
     *
     * @return Route[]
     */
    public function all(): array
    {
        return $this->routes;
    }

    /**
     * Add route.
     *
     * @param Route $route
     * @return $this
     */
    public function add(Route $route): RouteCollection
    {
        $this->routes[] = $route;

        return $this;
    }

    /**
     * Get route.
     *
     * @param string $key
     * @return Route|null
     */
    public function get(string $key): ?Route
    {
        foreach ($this->routes as $route) {
            if ($route->get_name() === $key) {
                return $route;
            }
        }

        return null;
    }

    /**
     * Match a route with request.
     *
     * @param Request $request
     * @return Route|null
     */
    public function match(Request $request): ?Route
    {
        foreach ($this->routes as $route) {
            if (request()->method() !== $route->method()) {
                continue;
            }

//            var_dump(app(Router::class)::get_pattern($route->uri()), $request->request_uri());

            if (preg_match(app(Router::class)::get_pattern($route->uri()), $request->request_uri())) {
                return $route;
            }
        }

        return null;
    }
}