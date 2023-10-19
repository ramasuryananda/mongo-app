<?php

namespace Tests\Feature\Package;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\Koli;
use App\Models\Connote;
use App\Models\Package;
use App\Services\PackageService;
use App\Repository\ConnoteRepository;
use App\Repository\KoliRepository;
use App\Repository\PackageRepository;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Request as FacadesRequest;

class GetPackageTest extends TestCase
{
    use WithFaker;
    protected $service;
    protected $header;
    protected function setUp():void
    {
        parent::setUp();
        Koli::truncate();
        Connote::truncate();
        Package::truncate();

        $this->service = $this->mock(PackageService::class);

        $this->header = [
            'Content-type'  => 'application/json',
            'Accept'        => 'application/json',
        ];
    }
    /**
     * A basic feature test example.
     */
    public function test_it_can_get_packages(): void
    {
        $packageData = Package::factory(10)->create();
        $packageData->each(function ($package){
            Koli::factory()->create([
                "connote_id" => Connote::factory()->create([
                    "transaction_id" => $package->transaction_id
                ])->connote_id
                ]);
        });
        $this->service->shouldReceive("getData")->andReturn(Package::all());
        $request = [
            "limit" => null,
            "offset" => null,
        ];

        $response = $this->json('GET', route('getAllPackage'), $request, $this->header);

        $response->assertOk();
        $response->assertJsonStructure([
            'message',
            'data' => [[
                'transaction_id',
                'customer_name',
                'customer_code',
                'transaction_amount',
                'transaction_discount',
                'transaction_additional_field',
                'transaction_payment_type',
                'transaction_state',
                'transaction_code',
                'transaction_order',
                'location_id',
                'organization_id',
                'created_at',
                'updated_at',
                'transaction_payment_type_name',
                'transaction_cash_amount',
                'transaction_cash_change',
                'customer_attribute' ,
                'connote',
                'connote_id',
                'origin_data',
                'destination_data',
                'koli_data',
                'custom_field',
                'current_location',
            ]]
        ]);
    }
    /**
     * A basic feature test example.
     */
    public function test_it_cannot_get_package_if_some_error_occurs(): void
    {
        $packageData = Package::factory()->create();
        
        $this->service->shouldReceive("getData")->andReturn(Package::all());
        $request = [
            "limit" => null,
            "offset" => null,
        ];
        $response = $this->json('GET', route('getAllPackage'), $request, $this->header);

        $response->assertInternalServerError();
        $response->assertJsonStructure([
            'message',
            'error' 
        ]);
    }
}
