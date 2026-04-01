<?php

namespace Gingerminds\LaravelCore\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

abstract class AbstractController extends BaseController
{
    use AuthorizesRequests {
        authorize as protected baseAuthorize;
    }
    use DispatchesJobs;
    use ValidatesRequests;

    /**
     * @param mixed $ability
     * @param mixed $arguments
     * @return bool
     */
    public function authorize($ability, $arguments = [])
    {
        return true;
    }
}
