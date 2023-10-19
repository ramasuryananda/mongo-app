<?php

namespace Tests\Unit\Repository\Package;

use App\Models\Package;
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
        $this->repo = new PackageRepository();
    }
    /**
     * A basic feature test example.
     */
    public function test_it_can_store_Package(): void
    {
        $this->assertTrue(Package::count() == 0);
        $data = Package::factory()->make()->toArray();
        $this->repo->store($data);
        $this->assertTrue(Package::count() == 1);
    }
}
