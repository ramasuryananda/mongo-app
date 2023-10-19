<?php

namespace Database\Factories;

use App\Models\Package;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Package>
 */
class PackageFactory extends Factory
{

    protected $model = Package::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "customer_name" => fake()->name(),
            "customer_code" => fake()->unique()->text(),
            "transaction_amount" => 0,
            "transaction_discount" => 0,
            "transaction_additional_field" => null,
            "transaction_payment_type" => fake()->text(5),
            "transaction_state" => 0,
            "transaction_code" => fake()->unique()->text(),
            "transaction_order"=> 1,
            "location_id"=> fake()->text(),
            "organization_id" => fake()->numberBetween(1,10),
            "transaction_payment_type_name" => fake()->text(),
            "transaction_cash_amount" => 0,
            "transaction_cash_change" => 0,
            "customer_attribute" => null,
            "origin_data" => null,
            "destination_data" => null,
            "custom_field" => null,
            "currentLocation" => null
        ];
    }
}
