<?php

namespace VanDmade\Cuztomisable\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use VanDmade\Respondify\Respondify;

class Controller extends BaseController
{

    use AuthorizesRequests, ValidatesRequests;

    public function success($parameters)
    {
        return Respondify::success($parameters);
    }

    public function error($error, $parameters = [])
    {
        return Respondify::error($error, $parameters);
    }

    public function debug($parameters)
    {
        return Respondify::debug($parameters);
    }

}
