<?php

namespace App\Traits;


trait ApiStatusTrait
{
    public $successStatus = 200;
    public $failureStatus = 500;
    public $validationFailureStatus = 422;


    public function success($data = [], $msg = NULL)
    {
        $response['success'] = true;
        $response['message'] = $msg ?? __("Successfully done");
        $response['data'] = $data;
        return response()->json($response, $this->successStatus);
    }

    public function failed($data = [], $msg = NULL)
    {
        $response['success'] = false;
        $response['message'] = $msg ?? __("Something went wrong");
        $response['data'] = $data;
        return response()->json($response, $this->failureStatus);
    }

    public function error($data = [], $msg = NULL, $code = NULL)
    {
        $response['success'] = false;
        $response['message'] = $msg ?? __("Something went wrong");
        $response['data'] = $data;
        return response()->json($response, $code ?? $this->failureStatus);
    }

    public function validationError($data = [], $msg = NULL)
    {
        $response['success'] = false;
        $response['message'] = $msg ?? __("Validation error");
        $response['data'] = $data;
        return response()->json($response, $this->validationFailureStatus);
    }
}
