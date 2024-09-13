<?php

namespace App\Http\Controllers;

use App\Models\Emploee;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class EmploeeController extends Controller
{
    function index()
    {
        return view('employee');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'firstName' => 'required|string',
            'lastName' => 'required|string',
            'joiningDate' => 'required|date',
            'profileImage' => 'nullable|file|max:2048'
        ]);

        // Generate Employee Code
        $lastEmployee = Emploee::latest()->first();
        $newId = 1;
        if ($lastEmployee) {
            $lastId = Str::after($lastEmployee->emp_code, 'EMP-');
            $newId = intval($lastId) + 1;
        }
        $employeeCode = 'EMP-' . str_pad($newId, 4, '0', STR_PAD_LEFT);

        // Handle Profile Image Upload
        $imagePath = null;
        if ($request->hasFile('profileImage')) {
            $imagePath = $request->file('profileImage')->store('profileImages', 'public');
        }

        // Store Employee Data in MySQL
        $employee = Emploee::create([
            'emp_code' => $employeeCode,
            'first_name' => $data['firstName'],
            'last_name' => $data['lastName'],
            'full_name' => $data['firstName'] . ' ' . $data['lastName'],
            'joining_date' => $data['joiningDate'],
            'profile_image' => $imagePath,
        ]);

        return response()->json(['success' => true, 'message' => 'Employee added successfully']);
    }
    public function getEmployees(Request $request)
    {
        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');

        $query = Emploee::query();

        if ($startDate && $endDate) {
            $query->whereBetween('joining_date', [$startDate, $endDate]);
        }

        $employees = $query->paginate(5);

        return response()->json($employees);
    }
}
