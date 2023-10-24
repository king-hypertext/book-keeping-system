<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Http\Request;

class ViewController extends Controller
{
    /**
     * show the form for user login
     */
    public function login(): View
    {
        return view('login');
    }

    /**
     * show the form for adding new user
     */
    public function signup(): View
    {
        return view('save');
    }
}
