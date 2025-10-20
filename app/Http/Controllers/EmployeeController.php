<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $q = Employee::query();

        if ($request->filled('department')) {
            $q->where('department', (string)$request->string('department'));
        }
        if ($request->filled('salary_min')) {
            $q->where('salary', '>=', (float)$request->input('salary_min'));
        }
        if ($request->filled('salary_max')) {
            $q->where('salary', '<=', (float)$request->input('salary_max'));
        }
        if ($request->filled('search')) {
            $s = (string)$request->string('search');
            $q->where(function($qq) use ($s) {
                $qq->where('name','like',"%$s%")
                   ->orWhere('email','like',"%$s%")
                   ->orWhere('position','like',"%$s%")
                   ->orWhere('department','like',"%$s%");
            });
        }

        return $q->orderBy('id','desc')->paginate(20);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'       => ['required','string','max:255'],
            'email'      => ['required','email','max:255','unique:employees,email'],
            'position'   => ['required','string','max:255'],
            'salary'     => ['required','numeric','min:0'],
            'department' => ['required','string','max:255'],
        ]);

        $data['created_by'] = $request->user()->id;

        $emp = Employee::create($data);
        return response()->json($emp, 201);
    }

    public function show(Employee $employee)
    {
        return $employee;
    }

    public function update(Request $request, Employee $employee)
    {
        $data = $request->validate([
            'name'       => ['sometimes','string','max:255'],
            'email'      => ['sometimes','email','max:255', Rule::unique('employees','email')->ignore($employee->id)],
            'position'   => ['sometimes','string','max:255'],
            'salary'     => ['sometimes','numeric','min:0'],
            'department' => ['sometimes','string','max:255'],
        ]);

        $employee->update($data);
        return $employee;
    }

    public function destroy(Employee $employee)
    {
        $employee->delete();
        return response()->json(['message' => 'Deleted']);
    }

    public function totalSalaryByDepartment()
    {
        $rows = Employee::select('department', DB::raw('SUM(salary) as total'))
            ->groupBy('department')
            ->orderBy('department')
            ->get();

        $out = [];
        foreach ($rows as $r) {
            $out[$r->department] = (float)$r->total;
        }

        return response()->json($out);
    }
}
