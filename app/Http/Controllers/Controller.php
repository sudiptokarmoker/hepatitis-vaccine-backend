<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public static function return_response($message = '', $isSuccess = null, $data = [], $count = 0, $status_code = 200)
    {
        return response()->json(['message' => $message, 'isSuccess' => $isSuccess, 'data' => $data, 'count' => $count], $status_code);
    }
}
