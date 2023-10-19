<?php

namespace Tests\Unit\Repository\Package;

use App\Models\Package;
use App\Repository\PackageRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GetCountTest extends TestCase
{
    use WithFaker;
    protected $repo;
    protected $package;
    protected function setUp():void
    {
        parent::setUp();
        Package::truncate();
        $this->package = Package::factory(10)->create();
        $this->repo = new PackageRepository();
    }
    /**
     * A basic feature test example.
     */
    public function test_it_can_get_package_count(): void
    {
        Package::findOrFail($this->package->first()->transaction_id)->delete();
        $this->assertEquals(10,$this->repo->getCount());
    }
}
