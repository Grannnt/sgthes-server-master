<?php

namespace Database\Seeders;

use App\Models\SchoolYear;
use Illuminate\Database\Seeder;

class SchoolYearSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $school_years = [
            [
                'school_year_from'  => '2021-06-03',
                'school_year_to'    => '2022-03-29',
                'created_by'        => 1
            ],
            [
                'school_year_from'  => '2022-06-03',
                'school_year_to'    => '2023-03-29',
                'created_by'        => 1
            ]
        ];

        foreach ($school_years as $key => $value) {
            SchoolYear::create($value);
        }
    }
}