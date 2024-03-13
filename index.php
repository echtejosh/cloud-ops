<?php

/*
|--------------------------------------------------------------------------
| Introduction
|--------------------------------------------------------------------------
|
| This application is made using our personal Framework. This Framework
| contains every tooling that makes a solid application abiding by PSR
| convention. Have fun
|
|--------------------------------------------------------------------------
*/

use Framework\Foundation\Config;
use Framework\Foundation\Session;
use Framework\Http\Kernel;

require_once 'autoload.php';
require_once 'Framework/helpers.php';
require_once 'routes/web.php';

Session::start();

Config::set_many(
    [
        'app' => include base_path('/config/app.php'),
        'api' => include base_path('/config/api.php')
    ]
);

app(Kernel::class)->handle(request());