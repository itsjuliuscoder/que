<?php


namespace que\middleware;


use que\http\HTTP;
use que\http\input\Input;
use que\route\Route;
use que\security\Middleware;
use que\security\MiddlewareResponse;

class CheckAuthentication extends Middleware
{

    public function handle(Input $input): MiddlewareResponse
    {
        $route = Route::getCurrentRoute();

        if ($route->isRequireLogin() === true && !is_logged_in()) {

            if (!empty($route->getRedirectUrl())) {

                redirect($route->getRedirectUrl(), [
                    [
                        'message' => sprintf("You don't have access to this route (%s), login and try again.",
                            current_url()),
                        'status' => INFO
                    ]
                ]);

            } else {

                $this->setAccess(false);
                $this->setTitle("Access Denied");
                $this->setResponse("You don't have access to the current route, login and try again.");
                $this->setResponseCode(HTTP::UNAUTHORIZED);
                return $this;
            }

        } elseif ($route->isRequireLogin() === false && is_logged_in()) {

            if (!empty($route->getRedirectUrl())) {

                redirect($route->getRedirectUrl(), [
                    [
                        'message' => sprintf("You don't have access to this route (%s), logout and try again.",
                            current_url()),
                        'status' => INFO
                    ]
                ]);

            } else {

                $this->setAccess(false);
                $this->setTitle("Access Denied");
                $this->setResponse("You don't have access to the current route, logout and try again.");
                $this->setResponseCode(HTTP::UNAUTHORIZED);
                return $this;

            }

        }

        return parent::handle($input); // TODO: Change the autogenerated stub
    }
}