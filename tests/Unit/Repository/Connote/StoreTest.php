<?php

namespace Tests\Feature\Unit\Repository\Connote;

use App\Models\Connote;
use App\Models\Package;
use App\Repository\ConnoteRepository;
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
        $this->repo = new ConnoteRepository();
    }
    /**
     * A basic feature test example.
     */
    public function test_it_can_store_connote(): void
    {
        $this->assertTrue(Connote::count() == 0);
        $data = Connote::factory()->make()->toArray();
        $this->repo->store($data);
        $this->assertTrue(Connote::count() == 1);
    }
}
