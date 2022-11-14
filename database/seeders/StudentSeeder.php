<?php

namespace Database\Seeders;

use App\Models\Student;
use Illuminate\Database\Seeder;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $students = [
            [
                'lrn'               => '2021010-0001',
                'access_code'       => $this->generate_lrn(6),
                'name'              => 'Juan de la Cruz',
                'sex'               => 'Male',
                'birthdate'         => '2020-01-01',
                'contact_no'        => '09123456789',
                'school_year_id'    => 1,
                'section_id'        => 1,
                'status'            => 1,
                'created_by'        => 1
            ],
            [
                'lrn'               => '2021010-0002',
                'access_code'       => $this->generate_lrn(6),
                'name'              => 'Jane de la Cruz',
                'sex'               => 'Female',
                'birthdate'         => '2021-01-01',
                'contact_no'        => '09123456780',
                'school_year_id'    => 1,
                'section_id'        => 2,
                'status'            => 1,
                'created_by'        => 1
            ]
        ];

        foreach ($students as $key => $value) {
            Student::create($value);
        }
    }

    public function generate_lrn($limit, $id = "")
    {
        return substr(number_format(time() * rand(), 0, '', ''), 0, $limit) . $id;
    }
}