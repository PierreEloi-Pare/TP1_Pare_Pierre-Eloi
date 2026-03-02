<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Equipment;
use App\Models\Rental;
use App\Models\Sport;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        
        $this->call([
            CategorySeeder::class,      
            SportSeeder::class,         
            EquipmentSeeder::class,     
            EquipmentSportSeeder::class 
        ]);

        //https://laravel.com/docs/12.x/eloquent-factories

        User::factory(10)
         ->has(Rental::factory(2)
            ->for(Equipment::factory()
                ->for(Category::factory()))
            )
        ->create();

    Sport::factory(10)->hasAttached(Equipment::factory(2)->for(Category::factory()))->create();
    
    //J'ai ensuite appris d'un collègue que ce seeding n'était plus requis et que cela avait été précisé en classe (tellement de temps perdu :/).
    //Chaque jour suffit sa peine. Voici la requête que j'ai finalement demandé au LLM chatGPT après plus d'une heure de recherches sans fruits:

    /* "What's the problem here? : SQLSTATE[23000]: Integrity constraint violation: 19 NOT NULL constraint failed: equipment_sport.created_at 
    (Connection: sqlite, Database: C:\Bureau\Cegep\Session_4\Service_Web\tps\TP1_Pare_Pierre-Eloi\database\database.sqlite, SQL: insert into 
    "equipment_sport" ("equipment_id", "sport_id") values (7, 6), (8, 6)) at vendor\laravel\framework\src\Illuminate\Database\Connection.php:838 834▕
     $exceptionType = $this->isUniqueConstraintError($e) 835▕ ? UniqueConstraintViolationException::class 836▕ : QueryException::class; 837▕ ➜ 838▕
      throw new $exceptionType( 839▕ $this->getNameWithReadWriteType(), 840▕ $query, 841▕ $this->prepareBindings($bindings), 842▕ $e, 1 
      vendor\laravel\framework\src\Illuminate\Database\Connection.php:584 PDOException::("SQLSTATE[23000]: Integrity constraint violation: 
      19 NOT NULL constraint failed: equipment_sport.created_at") 2 vendor\laravel\framework\src\Illuminate\Database\Connection.php:584 
      PDOStatement::execute() -- which is probably an issue with this: User::factory(10) ->has(Rental::factory(2) ->for(Equipment::factory() 
      ->for(Category::factory())) ) ->create(); Sport::factory(10)->has(Equipment::factory(2)->for(Category::factory()))->create();*/

    }
}
