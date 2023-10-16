<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employees;
use App\Models\EmployeeAddresses;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;


class EmployeesController extends Controller
{
    protected $user;
    public function __construct()
    {
        try{
        $this->user = JWTAuth::parseToken()->authenticate();
        }catch(\Exception $e){
            return response()->json(['success' => false,'error' => 'Unauhenticated User'], 401);

        }

    }


    public function employeesList(){
      $employeeList = Employees::with('employee_addresses')->get();
      return response()->json([
            'success' => true,
            'message' => 'Employee get successfully',
            'data' => $employeeList
            ], Response::HTTP_OK);
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(),
            [
                'name' => 'required',
                'email' => 'required|email|unique:users',
                'contact_number' => 'required|numeric',
                'salary' => 'required|numeric',
            ]);

         if ($validator->fails()) {
                return response()->json(['success' => false,'error'=>$validator->errors()], 400);
            }

            $name = $request->name;
            $email = $request->email;
            $contactNumber = $request->contact_number;
            $salary = $request->salary;
            $address = $request->address;

            $employee =  Employees::create([
                'name' => $name,
                'email' => $email,
                'contact_number' => $contactNumber,
                'salary' => $salary
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Employee created successfully',
                'data' => $employee
            ], Response::HTTP_OK);
    }

    public function update(Request $request){
        $validator = Validator::make($request->all(),
                [
                'e_id' => 'required'
                ]);

         if ($validator->fails()) {
            return response()->json(['success' => false,'error'=>$validator->errors()], 400);
        }

            $getEmployee = Employees::find($request->e_id);

            if($getEmployee){

            $getEmployee->name = ($request->name) ? $request->name : $getEmployee->name;
            $getEmployee->email = ($request->email) ? $request->email : $getEmployee->email;
            $getEmployee->contact_number = ($request->contact_number) ? $request->contact_number : $getEmployee->contact_number;
            $getEmployee->salary = ($request->salary) ? $request->salary : $getEmployee->salary;
            if($getEmployee->save()){
                return response()->json([
                    'success' => true,
                    'message' => 'Employee updated successfully',
                    'data' => $getEmployee
                ], Response::HTTP_OK);

            } else {
                return response()->json(['success' => false,'error' => 'something wrong'], 400);
            }

        } else {
            return response()->json(['success' => false,'error' => 'something wrong'], 400);
        }


      }


    public function delete(Request $request){

        $validator = Validator::make($request->all(),
        [
            'e_id' => 'required',
        ]);

     if ($validator->fails()) {
        return response()->json(['success' => false,'error'=>$validator->errors()], 400);
    }
        EmployeeAddresses::where('e_id',$request->e_id)->delete();
        $deleteEmployee = Employees::find($request->e_id);
        if($deleteEmployee->delete()){

            return response()->json([
                'success' => true,
                'message' => 'Employee deleted successfully',
            ], Response::HTTP_OK);
        }  else {
            return response()->json(['success' => false,'error' => 'something wrong'], 400);
        }

    }

    public function addAddress(Request $request){
        $validator = Validator::make($request->all(),
            [
                'e_id' => 'required',
                'address' => 'required'
            ]);

         if ($validator->fails()) {
            return response()->json(['success' => false,'error'=>$validator->errors()], 400);
        }

           $employessAddress =  EmployeeAddresses::create([
                'e_id' => $request->e_id,
                'address' => $request->address,
            ]);

            if($employessAddress)
            {
            return response()->json([
                'success' => true,
                'message' => 'Employee address saved successfully',
                'data' => $employessAddress
            ], Response::HTTP_OK);

          } else {
            return response()->json(['success' => false,'error'=>$validator->errors()], 400);
          }
      }
}
