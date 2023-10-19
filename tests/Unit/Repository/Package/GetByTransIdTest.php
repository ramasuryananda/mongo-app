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

class GetByTransIdTest extends TestCase
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
    public function test_it_can_get_package_by_trans_id(): void
    {
        $data = $this->repo->getByTransId($this->package->transaction_id);
        $this->assertEquals($this->package->transaction_id,$data->transaction_id);
        $this->assertEquals($this->package->customer_name,$data->customer_name);
        $this->assertEquals($this->package->customer_code,$data->customer_code);
        $this->assertEquals($this->package->transaction_amount,$data->transaction_amount);
        $this->assertEquals($this->package->transaction_discount,$data->transaction_discount);
        $this->assertEquals($this->package->transaction_additional_field,$data->transaction_additional_field);
        $this->assertEquals($this->package->transaction_payment_type,$data->transaction_payment_type);
        $this->assertEquals($this->package->transaction_state,$data->transaction_state);
        $this->assertEquals($this->package->transaction_code,$data->transaction_code);
        $this->assertEquals($this->package->transaction_order,$data->transaction_order);
        $this->assertEquals($this->package->location_id,$data->location_id);
        $this->assertEquals($this->package->organization_id,$data->organization_id);
        $this->assertEquals($this->package->transaction_payment_type_name,$data->transaction_payment_type_name);
        $this->assertEquals($this->package->transaction_cash_amount,$data->transaction_cash_amount);
        $this->assertEquals($this->package->transaction_cash_change,$data->transaction_cash_change);
        $this->assertEquals($this->package->customer_attribute,$data->customer_attribute);
        $this->assertEquals($this->package->origin_data,$data->origin_data);
        $this->assertEquals($this->package->destination_data,$data->destination_data);
        $this->assertEquals($this->package->custom_field,$data->custom_field);
        $this->assertEquals($this->package->currentLocation,$data->currentLocation);
    }
    public function test_it_cannot_get_package_by_trans_id_if_not_found(): void
    {
        try{
            $this->repo->getByTransId($this->package->transaction_id."test");

        }catch(Throwable $t){
            $this->assertInstanceOf(ModelNotFoundException::class,$t);
        }
        
    }
}
