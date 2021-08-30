<?php

namespace Database\Seeders;

use App\Domains\Directory\Models\Operator;
use Illuminate\Database\Seeder;

class Operators extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Operator::create([
            'address' => 'imap.yandex.ru',
            'port' => '993',
            'type' => 'SSL'
        ]);
    }
}
