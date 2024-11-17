<?php

namespace App\Http\Controllers;

use App\Jobs\SendRegistrationMail;
use App\Models\Employee;
use App\Models\Salary;
use App\Models\User;
use Carbon\Carbon;
use GuzzleHttp\Psr7\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class employeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // Get filter and search inputs
        $filter = $request->input('filter', 'name'); // Default filter is 'name'
        $search = $request->input('search', '');

        // Build the query with filtering and searching
        $query = Employee::with(['userDetails', 'departmentDetails']);

        if ($search) {
            switch ($filter) {
                case 'department':
                    $query->whereHas('departmentDetails', function ($q) use ($search) {
                        $q->where('department_name', 'like', '%' . $search . '%');
                    });
                    break;
                case 'designation':
                    $query->where('designation', 'like', '%' . $search . '%');
                    break;
                case 'name':
                default:
                    $query->whereHas('userDetails', function ($q) use ($search) {
                        $q->where('first_name', 'like', '%' . $search . '%')
                            ->orWhere('last_name', 'like', '%' . $search . '%');
                    });
                    break;
            }
        }

        $data = $query->orderBy('created_at')->paginate(10);

        return view('admin.employee', compact('user', 'data'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'password' => Hash::make($request->password)
        ]);
        if ($request->has('image')) {
            $file = $request->file('image');
            $extention = $file->getClientOriginalExtension();
            $user_logo_name = time() . "." . $extention;
            $file->move('userImages/', $user_logo_name);
            $user->image = $user_logo_name;
            $user->save();
        }
        Employee::create([
            'user_id' => $user->id,
            'department_id' => $request->department,
            'designation' => $request->designation,
            'joining_date' => $request->joining_date,
            'current_salary' => $request->current_salary,
        ]);
        SendRegistrationMail::dispatch($user,$request->password);
        return response()->json([
            "Message" => "employee created successfully"
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $Employee = Employee::find($id);
        $Employee->load('departmentDetails', 'userDetails');
        return view('admin.employeeView', compact('Employee'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data = Employee::find($id);
        return response()->json($data->load(['userDetails', 'departmentDetails']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
{
    // Validate the request
    $validator = Validator::make($request->all(), [
        'first_name' => 'required|string|max:255',
        'last_name' => 'required|string|max:255',
        // 'email' => 'required|email|exists:users,email',
        'phone_number' => 'required|numeric',
        'department' => 'required|exists:departments,id',
        'designation' => 'required|string|max:255',
        'joining_date' => 'required|date',
        'current_salary' => 'required|numeric',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'errors' => $validator->errors()
        ], 422);
    }

    // Find the employee record
    $employee = Employee::find($id);
    if (!$employee) {
        return response()->json([
            'error' => 'Employee not found'
        ], 404);
    }

    // Find the associated user record
    $user = User::find($employee->user_id);
    if (!$user) {
        return response()->json([
            'error' => 'User not found'
        ], 404);
    }

    // Handle image upload
    if ($request->hasFile('image')) {
        $oldImage = $user->image;
        if ($oldImage && $oldImage !== 'userlogo.png') {
            $oldImagePath = public_path("UserProfile/$oldImage");
            if (file_exists($oldImagePath)) {
                unlink($oldImagePath);
            }
        }

        $file = $request->file('image');
        $extension = $file->getClientOriginalExtension();
        $newImageName = time() . "." . $extension;
        $file->move(public_path('UserProfile'), $newImageName);
        $user->image = $newImageName;
    }

    // Update the employee record
    $employee->update([
        'department_id' => $request->department,
        'designation' => $request->designation,
        'joining_date' => $request->joining_date,
        'current_salary' => $request->current_salary,
    ]);

    // Update the user record
    $user->update([
        'first_name' => $request->first_name,
        'last_name' => $request->last_name,
        'email' => $request->email,
        'phone_number' => $request->phone_number,
    ]);

    return response()->json([
        'message' => 'Successfully updated'
    ]);
}


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // dd($id);
        $record = Employee::find($id);
        User::where('id', $record->user_id)->delete();
        $record->delete();
        return response()->json([
            "Message" => "record delete successfully"
        ]);
    }

}
