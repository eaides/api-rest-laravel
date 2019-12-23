<?php

namespace App\Helpers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class Helper
{
    /**
     * @return bool
     */
    public static function isApiRequest()
    {
        $self = new self;
        return $self->_isApiRequest();
    }

    public static function needApiValidation()
    {
        $self = new self;
        $isApiRequest = $self->_isApiRequest();
        $use_email_verification = config('app.use_email_verification');
        if ($isApiRequest && $use_email_verification)
        {
            return true;
        }
        return false;
    }

    /**
     * @return bool
     */
    protected function _isApiRequest()
    {
        $disable_web_routes = config('app.disable_web_routes');
        $prefix =  config('app.api_prefix');
        if ($disable_web_routes || Route::current()->getPrefix()==$prefix) {
            return true;
        }
        return false;
    }
}