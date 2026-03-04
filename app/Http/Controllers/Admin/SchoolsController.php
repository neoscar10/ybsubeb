<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SchoolsController extends Controller
{
    public function index()
    {
        return view('admin.schools.index');
    }
}
