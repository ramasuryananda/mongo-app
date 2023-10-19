<?php

namespace Tests\Feature\Unit\Repository\Koli;

use App\Models\Connote;
use App\Models\Koli;
use App\Models\Package;
use App\Repository\ConnoteRepository;
use App\Repository\KoliRepository;
use App\Repository\PackageRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class StoreTest extends TestCase
{
    protected $repo;
    protected function setUp():void
    {
        parent::setUp();
        Package::truncate();
        Connote::truncate();
        Koli::truncate();
        $this->repo = new KoliRepository();
    }
    /**
     * A basic feature test example.
     */
    public function test_it_can_store_connote(): void
    {
        $this->assertTrue(Koli::count() == 0);
        $data = Koli::factory()->make()->toArray();
        $this->repo->store($data);
        $this->assertTrue(Koli::count() == 1);
    }
}
