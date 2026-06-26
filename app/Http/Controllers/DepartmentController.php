<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function index()
    {
        $departments = Department::latest('id_departemen')->paginate(20);
        return view('departments.index', compact('departments'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'Nama_departemen' => 'required|string|max:255',
            'Keterangan' => 'nullable|string',
        ]);

        Department::create($data);

        return back()->with('success', 'Department created successfully.');
    }

    public function update(Request $request, Department $department)
    {
        $data = $request->validate([
            'Nama_departemen' => 'required|string|max:255',
            'Keterangan' => 'nullable|string',
        ]);

        $department->update($data);

        return back()->with('success', 'Department updated successfully.');
    }

    public function destroy(Department $department)
    {
        $department->delete();
        return back()->with('success', 'Department deleted successfully.');
    }
}
