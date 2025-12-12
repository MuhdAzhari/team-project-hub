<?php

namespace Database\Seeders;

use App\Models\Client;
use Illuminate\Database\Seeder;

class ClientSeeder extends Seeder
{
    public function run(): void
    {
        Client::create(['name' => 'Acme Corporation']);
        Client::create(['name' => 'TechSolutions Sdn Bhd']);
        Client::create(['name' => 'GlobalSoft Enterprise']);
    }
}
