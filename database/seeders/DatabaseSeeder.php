<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            Users::class,
            LeadSources::class,
            Operators::class,
            Integration::class,
            LeadStatuses::class,
            RetailCustomer::class
        ]);
    }
}
