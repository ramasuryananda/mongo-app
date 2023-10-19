<?php

namespace Tests\Unit\Repository\Connote;

use Tests\TestCase;
use App\Models\Connote;
use App\Models\Package;
use App\Repository\ConnoteRepository;
use App\Repository\PackageRepository;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UpdateTest extends TestCase
{
    use WithFaker;
    protected $repo;
    protected $connote;
    protected function setUp():void
    {
        parent::setUp();
        Package::truncate();
        Connote::truncate();
        $this->connote = Connote::factory()->create();
        $this->repo = new ConnoteRepository();
    }
    /**
     * A basic feature test example.
     */
    public function test_it_can_update_connote(): void
    {
        $updateData = [
            "location_id" => fake()->text(),
            "connote_state_id" => fake()->numberBetween(0,3),
            "location_name" => fake()->text()
        ];
        $this->repo->updateFromTransaction($updateData,$this->connote->transaction_id);
        $newData = $this->connote->fresh();

        $this->assertEquals($newData->connote_id, $this->connote->connote_id);
        $this->assertNotEquals($newData,$this->connote);
    }
}
