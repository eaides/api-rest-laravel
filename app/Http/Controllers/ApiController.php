<?php

namespace App\Http\Controllers;

use App\Traits\ApiResponser;
use App\Helpers\Helper;
use Illuminate\Routing\RouteFileRegistrar;

class ApiController extends Controller
{
    use ApiResponser;

    public function __construct()
    {
    }

}
