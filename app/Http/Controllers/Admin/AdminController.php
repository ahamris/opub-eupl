<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\AdminBaseController;

class AdminController extends AdminBaseController
{
    public function home()
    {
        return view('admin.home.index');
    }
}
