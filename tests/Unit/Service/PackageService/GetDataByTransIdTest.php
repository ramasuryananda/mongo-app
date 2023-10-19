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

class GetDataByTransIdTest extends TestCase
{
    use WithFaker;
    protected $service;
    protected $packageRepo;
    protected $package;
    protected function setUp():void
    {
        parent::setUp();
        Connote::truncate();
        Package::truncate();

        $this->package = Package::factory()->create();

        $this->packageRepo = $this->mock(PackageRepository::class);

        $this->service = $this->app->make(PackageService::class);
    }
    /**
     * A basic feature test example.
     */
    public function test_it_can_getData_package(): void
    {
        $this->packageRepo->shouldReceive("getByTransId")->andReturn($this->package);

        $result = $this->service->getByTransId($this->package->transaction_id);
        $this->assertEquals($result,$this->package);
    }
}
