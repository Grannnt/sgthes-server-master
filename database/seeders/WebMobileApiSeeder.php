<?php

namespace Database\Seeders;

use App\Models\WebMobileApi;
use Illuminate\Database\Seeder;

class WebMobileApiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        WebMobileApi::create([
            'title'     => "Modernfix Web Api",
            'api_key'   => 'xlpUGMb0Y1GDOx53KVysuRGU9sMcQhb4Vqlmmpk6q4o6VkEpn2ImJnVvesn5',
            'status'    => 1
        ]);
    }
}