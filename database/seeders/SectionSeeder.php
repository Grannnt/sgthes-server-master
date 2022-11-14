<?php

namespace Database\Seeders;

use App\Models\Section;
use Illuminate\Database\Seeder;

class SectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $sections = [
            [
                'section'       => 'Section 1',
                'created_by'    => 1
            ],
            [
                'section'       => 'Section 2',
                'created_by'    => 1
            ]
        ];

        foreach ($sections as $key => $value) {
            Section::create($value);
        }
    }
}