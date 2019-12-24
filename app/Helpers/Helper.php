<?php

namespace App\Helpers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Illuminate\Translation\Translator;
use Illuminate\Validation\Factory;
use Illuminate\Validation\Validator;

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

    /**
     * @param string $folder
     * @param int $mode
     * @param bool $recursive
     * @param bool $force
     * @return string
     */
    public static function getGetPublicSubFolder($folder='img', $mode = 0777, $recursive = true, $force = true)
    {
        $path = public_path($folder);
        if(!File::isDirectory($path)){
            File::makeDirectory($path, $mode, $recursive, $force);
        }
        return $path;
    }

    /**
     * @param bool $directory
     * @param bool $hidden
     * @param bool $only_images
     */
    public static function getFileNamesInFolder($directory=false, $hidden=false, $recursive=false, $only_images=false)
    {
        if ($directory===false) $directory = public_path('img');
        if ($recursive===false) $allFiles = File::files($directory, $hidden);
            else                $allFiles = File::allFiles($directory, $hidden);

        $files = [];
        foreach(File::allFiles($directory, $hidden) as $file)
        {
            if ($only_images)
            {
                $ext = strtolower(File::extension($file));
                if (!in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'svg', 'webp'])) continue;
            }
            $files[] = $file->getFilename();
        }
        return $files;
    }
}