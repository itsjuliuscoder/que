<?php
/**
 * Created by PhpStorm.
 * User: Wisdom Emenike
 * Date: 10/2/2020
 * Time: 10:23 PM
 */

namespace que\middleware;


use que\http\input\Input;
use que\security\Middleware;
use que\security\MiddlewareResponse;
use que\session\Session;

class StartSession extends Middleware
{
    public function handle(Input $input): MiddlewareResponse
    {
        Session::startSession();
        return parent::handle($input); // TODO: Change the autogenerated stub
    }
}