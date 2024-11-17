<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;

class departmentController extends Controller
{
    public function fetchDepartment(){
        $data = Department::get();
        return response()->json($data);
    }
}
