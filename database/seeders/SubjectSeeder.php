<?php

namespace Database\Seeders;

use App\Models\Subject;
use Illuminate\Database\Seeder;

class SubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $subjects = [
            [
                'subject'       => 'Subject 1',
                'created_by'    => 1
            ],
            [
                'subject'       => 'Subject 2',
                'created_by'    => 1
            ]
        ];

        foreach ($subjects as $key => $value) {
            Subject::create($value);
        }
    }
}