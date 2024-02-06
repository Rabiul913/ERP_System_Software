<?php

namespace Modules\HR\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\HR\Entities\Designation;

class DesignationTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $csvFile = 'Modules/HR/Database/Seeders/SeedingFiles/HMDepartments.csv'; // Path to your CSV file

        $headerLine = true;
        $delimiter = ',';

        if (($handle = fopen($csvFile, 'r')) !== false) {
            while (($data = fgetcsv($handle, 1000, $delimiter)) !== false) {
                if ($headerLine) {
                    $headerLine = false;
                    continue;
                }

                $designation = new Designation(); // Replace 'Employee' with your actual Employee model class

                // Map the CSV column values to the corresponding attributes of the Employee model
                $designation->name = $data[0];
                // $designation->com_id = $data[1];
                // Add more attributes if necessary

                $designation->save();
            }

            fclose($handle);
        }
    }
}
