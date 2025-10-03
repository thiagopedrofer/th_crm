<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Privilege;

class PrivilegeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $names = ['admin', 'super-admin', 'seller'];

        foreach ($names as $name) {
            Privilege::factory()->create([
                'name' => $name,
            ]);
        }
    }
}
