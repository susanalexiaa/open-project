<?php

namespace Database\Seeders;

use App\Domains\Integration\Models\Integration as IntegrationMain;
use Illuminate\Database\Seeder;

class Integration extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        IntegrationMain::create([
            'name' => 'Интеграция #1',
            'type' => 'Трекер почты',
            'operator_id' => 1,
            'login' => 'integrate@woodenshield.ru',
            'password' => 'yiEt4sEZ',
            'allowed_addresses' => 'info@woodenshield.ru',
            'is_active' => 1
        ]);
    }
}
