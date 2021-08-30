<?php

namespace Database\Seeders;

use App\Domains\Directory\Models\Locality;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LocalitiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Locality::truncate();
        $sql = file_get_contents(database_path() . '/databases/KLADR.sql');
        DB::statement($sql);
    }
}
