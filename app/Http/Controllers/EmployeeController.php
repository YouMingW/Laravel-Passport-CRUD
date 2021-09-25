<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\EmployeesImport;
use App\Transformers\EmployeeTransformer;

class EmployeeController extends Controller
{
    //
    public function index()
    {
        $employees = auth()->user()->employees;

        return response()->json([
            'success' => true,
            'data' => $employees
        ]);
    }

    public function show($id)
    {
        $employee = auth()->user()->employees()->find($id);

        if (!$employee) {
            return response()->json([
                'success' => false,
                'message' => 'Employee with id ' . $id . ' not found'
            ], 400);
        }

        return response()->json([
            'success' => true,
            'data' => $employee->toArray()
        ], 400);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'empname' => 'required',
            'designation' => 'required'
        ]);

        $employee = new Employee();
        $employee->empname = $request->empname;
        $employee->designation = $request->designation;

        if (auth()->user()->employees()->save($employee))
            return response()->json([
                'success' => true,
                'data' => $employee->toArray()
            ]);
        else
            return response()->json([
                'success' => false,
                'message' => 'Employee could not be added'
            ], 500);
    }

    public function update(Request $request, $id)
    {
        $employee = auth()->user()->employees()->find($id);

        if (!$employee) {
            return response()->json([
                'success' => false,
                'message' => 'Employee with id ' . $id . ' not found'
            ], 400);
        }

        $updated = $employee->fill($request->all())->save();

        if ($updated)
            return response()->json([
                'success' => true,
                'message' => 'Employee updated successfully'
            ]);
        else
            return response()->json([
                'success' => false,
                'message' => 'Employee could not be updated'
            ], 500);
    }

    public function destroy($id)
    {
        $employee = auth()->user()->employees()->find($id);

        if (!$employee) {
            return response()->json([
                'success' => false,
                'message' => 'Employee with id ' . $id . ' not found'
            ], 400);
        }

        if ($employee->delete()) {
            return response()->json([
                'success' => true,
                'message' => 'Employee deleted succesfully'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Employee could not be deleted'
            ], 500);
        }
    }

    public function indexFiltering(Request $request)
    {
        $filter = $request->query('filter');

        if (!empty($filter)) {
            $employee = Employee::sortable()
                ->where('employee.empname', 'like', '%'.$filter.'%')
                ->paginate(5);
        } else {
            $employee = Employee::sortable()
                ->paginate(5);
        }

        return view('employee.index-filtering')->with('employee', $employee)->with('filter', $filter);
    }

    public function indexPaging()
    {
        $employee = Employee::paginate(5);

        return view('employee.index-paging')->with('employee', $employee);
    }

    public function employees(Employee $employee){
    	$employees = $employee->all();

    	return fractal()
    		->collection($employees)
    		->transformWith(new EmployeeTransformer)
    		->toArray();
    }

    public function import(Request $request)
    {
        Excel::import(new EmployeesImport(), $request->file('file'));

            return redirect()->route('employee.index')
                ->with('success', 'Employees has been imported');
    }
}
