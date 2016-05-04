<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use ResponseBuilder;
use App\ErrorCode;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function formatValidationErrors(Validator $validator)
    {
        return [
          'error' => true,
          'error_messages' => $validator->errors()->first(),
        ];
    }

    protected function successResponse($data = []){
      return ResponseBuilder::success(['data' => $data]);
    }

    protected function errorResponse($errorCode, $msgCode = false, $msgParams = Array())
    {
      if($msgCode){
        $msg = trans($msgCode, $msgParams);
        return ResponseBuilder::errorWithMessage($errorCode, $msg, $errorCode);
      }
      return ResponseBuilder::errorWithHttpCode($errorCode, $errorCode);
    }

}
