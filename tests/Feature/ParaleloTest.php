<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Paralelo;

class ParaleloTest extends TestCase
{
    use RefreshDatabase;

    public function test_example(): void
    {
        Paralelo::factory()->create([
            'nombre' => 'Paralelo A',
        ]);
        Paralelo::factory()->create([
            'nombre' => 'Paralelo B',
        ]);

        $response = $this->getJson('/api/paralelos'); // ✅ ruta corregida

        $response->assertStatus(200)
            ->assertJsonFragment(['nombre' => 'Paralelo A'])
            ->assertJsonFragment(['nombre' => 'Paralelo B']);
    }
}
