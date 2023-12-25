<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\View;
use App\Interfaces\EmployeeRepositoryInterface;


class EmployeeController extends Controller
{
    /**
     * Login check and Logout
     *
     * @author yarzartinshwe
     *
     * @created 2023-6-21
     *
     */

    protected $employeeRepositoryInterface;

    /**
     * Constructor to assign interface to variables
     */
    public function __construct(EmployeeRepositoryInterface $employeeRepositoryInterface)
    {
        $this->employeeRepositoryInterface = $employeeRepositoryInterface;
    }

    /**
     * Login UI
     *
     * @author yarzartinshwe
     * @created 2023-6-21
     *
     */
    public function login()
    {
        return view('login');
    }

    /**
     * Login data checking method
     *
     * @author yarzartinshwe
     * @created 2023-6-21
     * @param $request
     *
     */
    public function loginCheck(LoginRequest $request)
    {
        //login request data
        $employee_id = $request->employee_id;
        $password = $request->password;

        // Check the employees table for a matching ID and password
        $employee = $this->employeeRepositoryInterface->getEmployeeByEmployeeId($employee_id);
        // if ($employee && Hash::check($password, $employee->password)) {
        //     // Authentication successful, redirect to the next blade file
        //     return redirect()->route('items.show');
        // }
        if ($employee) {
            // Authentication successful, redirect to the next blade file
            return redirect()->route('items.show');
        }

        // Authentication failed, show error message           
        $employeeIdValue =  $this->employeeRepositoryInterface->getEmployeeIdForLoginCheck($employee_id);
        $passwordValue =  $this->employeeRepositoryInterface->getEmployeePasswordForLoginCheck($employee_id);

        //check employee id
        if ($employeeIdValue != $employee_id || $passwordValue != $password) {
            return redirect()->back()->with('error', 'Invalid employee ID or password');
        }
    }

    /**
     * Logout method
     *
     * @author yarzartinshwe
     * @created 2023-6-21
     *
     */
    public function logout()
    {
        //logout message to redirect to login page
        return redirect()->route('login')->with('success', 'Logout Successful!');
    }
}
