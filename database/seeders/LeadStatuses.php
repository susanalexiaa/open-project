<?php

namespace Database\Seeders;

use App\Domains\Lead\Models\LeadStatus;
use Illuminate\Database\Seeder;

class LeadStatuses extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        LeadStatus::firstOrCreate(
            [
                'name' => 'Новая заявка получена',
                'color_class' => 'red-400'
            ]
        );
        LeadStatus::firstOrCreate(
            [
                'name' => 'Квалификация пройдена',
                'color_class' => 'green-100'
            ]
        );
        LeadStatus::firstOrCreate(
            [
                'name' => 'Расчет отправлен',
                'color_class' => 'green-200'
            ]
        );
        LeadStatus::firstOrCreate(
            [
                'name' => 'Счет оплачен',
                'color_class' => 'green-400'
            ]
        );
        LeadStatus::firstOrCreate(
            [
                'name' => 'Отправлен',
                'color_class' => 'blue-100'
            ]
        );
        LeadStatus::firstOrCreate(
            [
                'name' => 'Успешно завершен',
                'color_class' => 'blue-400'
            ]
        );
        LeadStatus::firstOrCreate(
            [
                'name' => 'Отказ',
                'color_class' => 'gray-100'
            ]
        );
    }
}
