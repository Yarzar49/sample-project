<?php
namespace App\Interfaces;

interface EmployeeRepositoryInterface
{
    /**
     * EmployeeRepositoryInterface
     * @author yarzartinshwe
     * @created 2023-7-5
     */

     public function getEmployeeByEmployeeId($employeeId);

     public function getEmployeeIdForLoginCheck($employeeId);

     public function getEmployeePasswordForLoginCheck($employeeId);
   

}