<?php

namespace Tests\Unit\Repository\Package;

use App\Models\Package;
use App\Repository\PackageRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GetAllTest extends TestCase
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
    public function test_it_can_get_all_package_without_offset_and_limit(): void
    {
        Package::findOrFail($this->package->first()->transaction_id)->delete();

        $datas = $this->repo->getAll(null,null);
        
        $this->assertCount(9,$datas);
        $this->package->fresh();

        foreach($datas as $key => $data){
            $this->assertTrue($this->package->contains(function ($item,$key) use($data){
                return $item->transaction_id == $data->transaction_id;
            }));
        }
    }
    
    public function test_it_can_get_all_package_with_offset_and_limit(): void
    {
        Package::findOrFail($this->package->first()->transaction_id)->delete();

        $datas = $this->repo->getAll(offset:7,limit:5);
        
        $this->assertCount(2,$datas);
        $this->package->fresh();

        foreach($datas as $key => $data){
            $this->assertTrue($this->package->contains(function ($item,$key) use($data){
                return $item->transaction_id == $data->transaction_id;
            }));
        }
    }
}
