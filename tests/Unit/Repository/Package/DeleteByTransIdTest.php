<?php

namespace Tests\Feature\Unit\Repository\Package;

use App\Models\Package;
use App\Repository\PackageRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Throwable;

class DeleteByTransIdTest extends TestCase
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
    public function test_it_can_delete_package_by_trans_id(): void
    {
        $this->repo->deleteByTransId($this->package->transaction_id);
        try {
            $this->repo->getByTransId($this->package->transaction_id);
        } catch (\Throwable $th) {
            $this->assertInstanceOf(ModelNotFoundException::class,$th);
        }
    }
    public function test_it_cannot_get_delete_by_trans_id_if_not_found(): void
    {
        try{
            $this->repo->getByTransId($this->package->transaction_id."test");

        }catch(Throwable $t){
            $this->assertInstanceOf(ModelNotFoundException::class,$t);
        }
        
    }
}
