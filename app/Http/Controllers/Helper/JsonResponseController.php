<?php

namespace App\Http\Controllers\Helper;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class JsonResponseController extends Controller
{

    public function sendPaginateResponse($result, $code= 200){
        $response = [
            'data'    => $result,
            'Content-Type'=> 'application/json'
        ];
        return response()->json($response, $code);
    }

    public function sendResponse($result, $message, $code = 200)
    {
        $response = [
            'result' => 1,
            'data'    => $result,
            'message' => $message,
        ];
        return response()->json($response, $code);
    }

    public function sendEmptyResponse($result, $message)
    {
        $response = [
            'result' => 0,
            'data'    => $result,
            'message' => $message,
        ];
        return response()->json($response, 200);
    }

    public function sendError($error, $errorMessages = [], $code = 404)
    {
        $response = [
            'result' => 2,
            'message' => $error,
        ];
        if(!empty($errorMessages)){
            $response['data'] = $errorMessages;
        }
        return response()->json($response, $code);
    }


}
