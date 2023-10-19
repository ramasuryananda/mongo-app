<?php

namespace Database\Factories;

use App\Models\Connote;
use App\Models\Koli;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Koli>
 */
class KoliFactory extends Factory
{
    protected $model = Koli::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "koli_length" => 0,
            "awb_url" => fake()->url(),
            "koli_chargeable_weight" => 0,
            "koli_width" => 0,
            "koli_surcharge" => 0,
            "koli_height" => 0,
            "koli_description" => 0,
            "koli_formula_id" => null,
            "connote_id" => function(){
                Connote::factory()->create()->connote_id;
            },
            "koli_volume" => 0,
            "koli_weight" => 0,
            "koli_custom_field" => null,
            "koli_code" => fake()->unique()->text(),
        ];
    }
}
