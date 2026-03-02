<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;


class EquipmentSportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
   public function run(): void
    {
        DB::unprepared(
            File::get(database_path('seeders\sql\equipment_sport.sql'))
        );
        //Voir CategorySeeder
    }
}
