<?php

namespace Database\Seeders;

use App\Models\Project;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    public function run(): void
    {
        Project::create([
            'client_id' => 1,
            'name' => 'Website Redesign',
            'description' => 'A full redesign of client corporate website.',
            'status' => 'active', // valid
        ]);

        Project::create([
            'client_id' => 2,
            'name' => 'Mobile App MVP',
            'description' => 'An MVP mobile application project.',
            'status' => 'planned', // valid
        ]);

        Project::create([
            'client_id' => 3,
            'name' => 'Internal ERP Upgrade',
            'description' => 'Upgrade legacy ERP modules.',
            'status' => 'active', // changed from in_progress to active
        ]);
    }
}
