<?php

namespace Tests\Feature;

use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Equipment;
use App\Models\Rental;
use App\Models\Review;
use App\Models\User;

class EquipementTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_all_equipments(){
        Equipment::factory(3)
            ->for(Category::factory())
            ->create();
        $response = $this->get('/api/equipments');
        $response->assertStatus(200);
        $response->assertJsonCount(3, 'data');
    }

    public function test_get_equipment(){
        $equipment = Equipment::factory()
            ->for(Category::factory())
            ->create();

        $response = $this->get("/api/equipments/{$equipment->id}");

        $response->assertStatus(200);

        $response->assertJsonFragment([
            'id' => $equipment->id
        ]);
    }

    public function test_get_equipment_not_found(){
        $response = $this->get('/api/equipments/999');

        $response->assertStatus(404);
    }

    public function test_equipment_popularity(){
        $equipment = Equipment::factory()
            ->has(Rental::factory(2)
                ->for(User::factory())
                ->has(Review::factory(2)
                    ->for(User::factory()))
                )
            ->for(Category::factory())
            ->create();

        $response = $this->get("/api/equipments/{$equipment->id}/popularity");

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'equipment_id',
            'equipment_name',
            'popularity'
        ]);
    }

    public function test_equipment_popularity_not_found(){
        $response = $this->get('/api/equipments/999/popularity');

        $response->assertStatus(404);
    }


    public function test_equipment_average_price(){
        $equipment = Equipment::factory()
            ->has(Rental::factory(4)
                ->for(User::factory())
                )
            ->for(Category::factory())
            ->create();

        $response = $this->get("/api/equipments/{$equipment->id}/average-price");

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'equipment_id',
            'average_price'
        ]);
    }

    public function test_equipment_average_price_not_found(){
        $response = $this->get('/api/equipments/999/average-price');

        $response->assertStatus(404);
    }
}
