<?php

namespace App\Http\Controllers\Helper;

use App\Http\Controllers\Controller;

class JsonResponseController extends Controller
{

    /**
     * @param $result
     * @param $code
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendPaginateResponse($result, $code = 200)
    {
        $response = [
            'data' => $result,
            'Content-Type' => 'application/json'
        ];
        return response()->json($response, $code);
    }

    /**
     * @param $result
     * @param $message
     * @param $code
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendResponse($result, $message, $code)
    {
        $response = [
            'result' => 1,
            'data' => $result,
            'message' => $message,
        ];
        return response()->json($response, $code);
    }

    /**
     * @param $message
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendError($message)
    {
        $response = [
            'result' => 0,
            'message' => $message,
        ];
        return response()->json($response, 404);
    }

}
