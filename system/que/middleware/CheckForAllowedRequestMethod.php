<?php


namespace que\middleware;


use que\http\HTTP;
use que\http\input\Input;
use que\route\Route;
use que\security\Middleware;
use que\security\MiddlewareResponse;

class CheckForAllowedRequestMethod extends Middleware
{
    public function handle(Input $input): MiddlewareResponse
    {
        $route = Route::getCurrentRoute();

        if (!in_array($method = $input->getRequest()->getMethod(), $route->getAllowedMethods())) {

            $this->setAccess(false);
            $this->setTitle("Unsupported Request Method");
            $this->setResponse("The {$method} method is not supported for this route. Supported methods: "
                . (implode(", ", $route->getAllowedMethods()) ?: "None") . "."
            );
            $this->setResponseCode(HTTP::METHOD_NOT_ALLOWED);
            return $this;
        }
        return parent::handle($input); // TODO: Change the autogenerated stub
    }
}