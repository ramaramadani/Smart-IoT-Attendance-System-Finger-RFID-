<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Department;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function index()
    {
        $employees = Employee::with('department')->latest('id_Karyawan')->paginate(20);
        $departments = Department::orderBy('Nama_departemen')->get();
        return view('employees.index', compact('employees', 'departments'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'Nip' => 'required|string|unique:karyawan,Nip',
            'Nama' => 'required|string|max:255',
            'Jenis_Kelamin' => 'required|in:Laki-laki,Perempuan',
            'Jabatan' => 'required|string|max:255',
            'id_departemen' => 'required|exists:departemen,id_departemen',
            'id_fingerprint' => 'nullable|string|max:255',
            'id_RFID' => 'nullable|string|max:255',
            'Tanggal_bergabung' => 'required|date',
            'Status' => 'required|in:aktif,nonaktif',
        ]);

        Employee::create($data);

        return back()->with('success', 'Employee created successfully.');
    }

    public function update(Request $request, Employee $employee)
    {
        $data = $request->validate([
            'Nip' => 'required|string|unique:karyawan,Nip,' . $employee->id_Karyawan . ',id_Karyawan',
            'Nama' => 'required|string|max:255',
            'Jenis_Kelamin' => 'required|in:Laki-laki,Perempuan',
            'Jabatan' => 'required|string|max:255',
            'id_departemen' => 'required|exists:departemen,id_departemen',
            'id_fingerprint' => 'nullable|string|max:255',
            'id_RFID' => 'nullable|string|max:255',
            'Tanggal_bergabung' => 'required|date',
            'Status' => 'required|in:aktif,nonaktif',
        ]);

        $employee->update($data);

        return back()->with('success', 'Employee updated successfully.');
    }

    public function destroy(Employee $employee)
    {
        $employee->delete();
        return back()->with('success', 'Employee deleted successfully.');
    }
}
