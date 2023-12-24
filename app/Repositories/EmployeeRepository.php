<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use App\Interfaces\EmployeeRepositoryInterface;

class EmployeeRepository implements EmployeeRepositoryInterface
{
    /**
     * EmployeeRepository
     *
     * @author yarzartinshwe
     *
     * @created 2023-7-5
     *
     */


    /**
     * Get Employee By EmployeeId
     *
     * @author yarzartinshwe
     * @param $employeeId
     * @created 2023-7-5
     *
     */
    public function getEmployeeByEmployeeId($employeeId)
    {
        $employee = DB::table('employees')->where('employee_id', $employeeId)->first();
        return $employee;
    }

    
    /**
     * Get Employee Id For LoginCheck
     *
     * @author yarzartinshwe
     * @param $employeeId
     * @created 2023-7-5
     *
     */
    public function getEmployeeIdForLoginCheck($employeeId)
    {
        $employeeIdValue = DB::table('employees')->where('employee_id', $employeeId)->pluck('employee_id')->first();
        return $employeeIdValue;

    }

     /**
     * Get Employee Password For LoginCheck
     *
     * @author yarzartinshwe
     * @param $employeeId
     * @created 2023-7-5
     *
     */
    public function getEmployeePasswordForLoginCheck($employeeId)
    {
        $passwordValue = DB::table('employees')->where('employee_id', $employeeId)->pluck('password')->first();
        return $passwordValue;
    }
}
