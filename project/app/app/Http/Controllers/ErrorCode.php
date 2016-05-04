<?php

namespace App\Http\Controllers;

class ErrorCode extends Controller
{
    // error code list
    const UNKNOWN_ERROR = 500;
    const BAD_REQUEST = 401;
    const NOT_AUTHORIZED = 401;
    const FORBIDDEN = 403;
    const NOT_FOUND = 404;
}
