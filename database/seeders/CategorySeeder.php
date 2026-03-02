<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::unprepared(
            File::get(database_path('seeders\sql\categories.sql')) 
            //J'ai demandé de l'aide à l'IA pour ce bout de code après n'avoir rien trouvé sur le seeding à partir de fichier SQL 
            //(ni dans les notes de cours ni dans la documentation fournie) puisqu'il est demandé de " Créer un seeder pour ces 4 tables en implantant le seed via le fichier sql".
            //Voici le prompt envoyé au LLM ChatGPT: "How do you seed a database using an sql file in laravel?"
        );
    }
}
