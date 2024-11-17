<?php

namespace App\Http\Controllers;

use App\Models\Holiday;
use Illuminate\Http\Request;

class HolidayController extends Controller
{
    public function createHoliday(Request $request){
        Holiday::create([
            'holiday_date'=>$request->holidayDate,
            'holiday_name'=>$request->holidayName,
            'holiday_description'=>$request->holidayDescription,
        ]);
        return response()->json(['message'=>'holiday added successfully']);
    }
}
