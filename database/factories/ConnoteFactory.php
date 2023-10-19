<?php

namespace Database\Factories;

use App\Models\Connote;
use App\Models\Package;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Connote>
 */
class ConnoteFactory extends Factory
{
    protected $model = Connote::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "connote_number" => fake()->randomNumber(),
            "connote_service" => fake()->text(),
            "connote_service_price" => 0,
            "connote_amount" => 0,
            "connote_code" => fake()->unique()->text(),
            "connote_booking_code" => null,
            "connote_order" => 1,
            "connote_state_id" => fake()->numberBetween(0,3),
            "zone_code_from" => fake()->text(),
            "zone_code_to" => fake()->text(),
            "surcharge_amount" => null,
            "transaction_id" => function () {
                return Package::factory()->create()->transaction_id;
            },
            "actual_weight" => 0,
            "volume_weight" => 0,
            "chargeable_weight"=> 0, 
            "organization_id" => fake()->numberBetween(0,10),
            "location_id" => fake()->text(),
            "connote_total_package"=> 0,
            "connote_surcharge_amount" => 0,
            "connote_sla_day" => 0,
            "location_name" => fake()->text(),
            "location_type" => fake()->text(),
            "source_tariff_db" => null,
            "id_source_tariff" => null,
            "pod" => null,
            "history" => null
        ];
    }
}
