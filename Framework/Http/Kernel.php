<?php

namespace Framework\Http;

use Framework\Foundation\ParameterBag;
use Framework\Foundation\View;
use Framework\Routing\Router;

/**
 * This class is the central component of our application responsible for handling HTTP requests
 * and preparing responses, including middleware processing and event emits.
 *
 * @package Framework\Http
 */
class Kernel
{
    /**
     * Router instance.
     *
     * @var Router
     */
    protected Router $router;

    /**
     * Kernel constructor.
     *
     * @param Router $router
     */
    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    /**
     * Handle HTTP request.
     *
     * @param Request $request The incoming HTTP request to be handled.
     * @return Response|null
     */
    public function handle(Request $request): ?Response
    {
        $response = $this->prepare_response($request, $this->router::dispatch($request));

        if (is_a($response, Response::class)) {
            echo $response->send();
        }

        $this->terminate($request, $response);

        return $response;
    }

    /**
     * Perform any final actions for the request lifecycle.
     *
     * @param Request $request The HTTP request object.
     * @return void
     */
    protected function terminate(Request $request, $response)
    {
        $request->session()->forget(['flash', 'errors']);
    }

    /**
     * Prepare a response for the request.
     *
     * @param Request $request The HTTP request object.
     * @param View|RedirectResponse|JsonResponse|null $response The response to be prepared.
     * @return Response|null
     */
    private function prepare_response(Request $request, $response): ?Response
    {
        if ($response instanceof RedirectResponse) {
            $request->flash();

            return null;
        }

        if ($response instanceof JsonResponse) {
            return $response;
        }

        if ($response instanceof View) {
            return new Response($response->render(), 200, $response->get_headers());
        }

        return new Response(view('errors.404')->render(), 404, new HeaderBag());
    }
}