<?php

namespace App\Http\Middleware;

use Closure;
// JWT
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
// User Model
use App\User;
// API return and error codes
use ResponseBuilder;
use App\Http\Controllers\ErrorCode;

class JwtAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // make sure we add teh request to JWTAuth when in testing env
        // this fixes issue with PHPUnit testing and JWTAuth
        if (env('APP_ENV') === 'testing') {
            JWTAuth::setRequest($request);
        }

        try {
            if (!$user = JWTAuth::parseToken()->authenticate()) {
                return $this->errorResponse(ErrorCode::NOT_FOUND);
            } else {
                // add user to request
                $request->merge(['authUser' => $user]);
            }
        } catch (TokenExpiredException $e) {
            return $this->errorResponse(ErrorCode::FORBIDDEN, 'api.token_expired');
        } catch (TokenInvalidException $e) {
            return $this->errorResponse(ErrorCode::FORBIDDEN, 'api.token_invalid');
        } catch (JWTException $e) {
            return $this->errorResponse(ErrorCode::FORBIDDEN, 'api.token_absent');
        }

        return $next($request);
    }

    private function successResponse($data = null)
    {
        return ResponseBuilder::success($data);
    }

    protected function errorResponse($errorCode, $msgCode = false, $msgParams = array())
    {
        if ($msgCode) {
            $msg = trans($msgCode, $msgParams);
            return ResponseBuilder::errorWithMessage($errorCode, $msg, $errorCode);
        }

        return ResponseBuilder::errorWithHttpCode($errorCode, $errorCode);
    }
}
