<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    /**
     * Get Current Request details as array
     * @return Array
     * @throws conditon
     **/
    protected function ReqContext(Request $request): array
    {
        $context['request-route'] = Route::currentRouteName();
        $context['request-data'] = $request->all();
        return $context;
    }
}