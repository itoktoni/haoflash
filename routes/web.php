<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Modules\System\Http\Controllers\HomeController;

Auth::routes(['verify' => true]);
