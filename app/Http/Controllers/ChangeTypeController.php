<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ChangeTypeController extends Controller
{
    public function changetype(){
        return view('admin.change_type.change_type');
    }
}
