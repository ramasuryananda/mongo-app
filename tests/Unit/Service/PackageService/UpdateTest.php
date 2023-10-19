<?php

namespace Tests\Feature\Unit\Service\PackageService;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\Koli;
use App\Models\Connote;
use App\Models\Package;
use App\Services\PackageService;
use App\Repository\ConnoteRepository;
use App\Repository\KoliRepository;
use App\Repository\PackageRepository;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UpdateTest extends TestCase
{
    use WithFaker;
    protected $service;
    protected $packageRepo;
    protected $connoteRepo;
    protected $package;
    protected $connote;
    protected function setUp():void
    {
        parent::setUp();
        Connote::truncate();
        Package::truncate();

        $this->package = Package::factory()->create();
        $this->connote = Connote::factory()->create([
            "transaction_id" => $this->package->transaction_id
        ]);

        $this->packageRepo = $this->mock(PackageRepository::class);
        $this->connoteRepo = $this->mock(ConnoteRepository::class);

        $this->service = $this->app->make(PackageService::class);
    }

    private function createRequestData() : array
    {
        return [
            "transaction_state" => 3,
            "location_id" => fake()->text(),
            "currentLocation"=>[
                "name" => fake()->name(),
                "code" => fake()->text(),
                "type" => fake()->text(),
            ]
        ];
    }
    /**
     * A basic feature test example.
     */
    public function test_it_can_udpate_package(): void
    {
        $requestData = $this->createRequestData();

        $this->packageRepo->shouldReceive("update")->andReturn(true);
        $this->connoteRepo->shouldReceive("updateFromTransaction")->andReturn(true);
        
        $updatedData = clone $this->package;
        $updatedData->transaction_state = $requestData["transaction_state"];
        $updatedData->location_id = $requestData["location_id"];
        $updatedData->currentLocation = $requestData["currentLocation"];
        $this->packageRepo->shouldReceive("getByTransId")->andReturn($updatedData);
        
        $result = $this->service->update($requestData,$this->package->transaction_id);
        $this->assertNotEquals($result,$this->package);
    }
}
