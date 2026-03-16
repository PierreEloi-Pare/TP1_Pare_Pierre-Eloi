<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Equipment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Review;
use App\Models\User;
use App\Models\Rental;

class ReviewTest extends TestCase
{
    use RefreshDatabase;

    public function test_delete_review(){
        $review = Review::factory()
        ->for(User::factory())
        ->for(Rental::factory()
            ->for(User::factory())
            ->for(Equipment::factory()
                ->for(Category::factory())
                )
            )
        ->create();

        $response = $this->delete("/api/reviews/{$review->id}");

        $response->assertStatus(204);

        $this->assertDatabaseMissing('reviews', [
            'id' => $review->id
        ]);
    }

    public function test_delete_review_not_found(){
        $response = $this->delete('/api/reviews/999');

        $response->assertStatus(404);
    }
}
