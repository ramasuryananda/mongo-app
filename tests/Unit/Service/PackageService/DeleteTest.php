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

class DeleteTest extends TestCase
{
    use WithFaker;
    protected $service;
    protected $packageRepo;
    protected $connoteRepo;
    protected $koliRepo;
    protected $package;
    protected function setUp():void
    {
        parent::setUp();
        Connote::truncate();
        Package::truncate();

        $this->package = Package::factory()->create();

        $this->packageRepo = $this->mock(PackageRepository::class);
        $this->connoteRepo = $this->mock(ConnoteRepository::class);
        $this->koliRepo = $this->mock(KoliRepository::class);

        $this->service = $this->app->make(PackageService::class);
    }
    /**
     * A basic feature test example.
     */
    public function test_it_can_delete_package(): void
    {
        $package = Package::factory()->create();
        $connote = Connote::factory()->create([
            "transaction_id" => $package->transaction_id
        ]);
        Koli::factory()->create([
            "connote_id" => $connote->connote_id
        ]);
        $this->packageRepo->shouldReceive("getByTransId")->andReturn($package);
        $this->koliRepo->shouldReceive("deleteByConnoteId");
        $this->connoteRepo->shouldReceive("deleteByTransId");
        $this->packageRepo->shouldReceive("deleteByTransId");
        $this->service->deletePackage($this->package->transaction_id);
        $this->assertTrue(true);
    }
}
