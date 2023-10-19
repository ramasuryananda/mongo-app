<?php

namespace Tests\Feature\Unit\Repository\Package;

use App\Models\Package;
use App\Repository\PackageRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UpdateTest extends TestCase
{
    use WithFaker;
    protected $repo;
    protected $package;
    protected function setUp():void
    {
        parent::setUp();
        Package::truncate();
        $this->package = Package::factory()->create();
        $this->repo = new PackageRepository();
    }
    /**
     * A basic feature test example.
     */
    public function test_it_can_update_Package(): void
    {
        $updateData = [[
            "transaction_state" => fake()->numberBetween(0,3),
            "location_id" => fake()->text(),
            "currentLocation" => fake()->array(),
        ]];
        $this->repo->update($updateData,$this->package->transaction_id);
        $newData = $this->package->fresh();

        $this->assertEquals($newData->transaction_id, $this->package->transaction_id);
        $this->assertNotEquals($newData,$this->package);
    }
}
