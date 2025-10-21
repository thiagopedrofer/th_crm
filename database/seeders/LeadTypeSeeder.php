<?php

namespace Database\Seeders;

use App\Models\LeadType;
use Illuminate\Database\Seeder;

class LeadTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $names = ['Facebook', 'Instagram', 'Indicação', 'TikTok', 'Site', 'Evento Presencial', 'ADS Empresa'];

        foreach ($names as $name) {
            LeadType::factory()->create([
                'name' => $name,
            ]);
        }
    }
}
