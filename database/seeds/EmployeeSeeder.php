<?php

use App\Models\Employee;
use Illuminate\Database\Seeder;

class EmployeeSeeder extends Seeder
{

    /**
     * Explanation or Description of this Class
     *
     * @author yarzartinshwe
     *
     * @created 2023-6-21
     *
     */
    
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Employee::class, 10)->create();
    }
}
