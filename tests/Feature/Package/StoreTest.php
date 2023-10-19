<?php

namespace Tests\Feature\Package;

use App\Http\Requests\StoreRequest;
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
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Request as FacadesRequest;

class StoreTest extends TestCase
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

    private function createRequestData() : array
    {
        return [
            "customer_name" => fake()->name,
            "customer_code"=> fake()->text(),
            "transaction_amount" => 0,
            "transaction_discount" => 0,
            "transaction_additional_field" => null,
            "transaction_payment_type" => fake()->randomNumber(),
            "transaction_state" => fake()->numberBetween(0,3),
            "location_id" => fake()->text(),
            "organization_id" => fake()->numberBetween(),
            "transaction_payment_type_name" => fake()->text(),
            "transaction_cash_amount" => 0,
            "transaction_cash_change" => 0,
            "customer_attribute" => [
                "Nama_Sales" => fake()->name(),
                "TOP" => fake()->text(),
                "Jenis_Pelanggan" => fake()->text()
            ],
            "connote" => [
                "connote_number" => fake()->randomNumber(),
                "connote_service" => fake()->text(),
                "connote_booking_code" => null,
                "zone_code_from" => fake()->text(),
                "zone_code_to" => fake()->text(),
                "surcharge_amount" => null,
                "actual_weight" => 0,
                "volume_weight" => 0,
                "chargeable_weight"=> 0,
                "connote_order" => 1,
                "connote_total_package"=> 0,
                "connote_surcharge_amount" => 0,
                "connote_sla_day" => 0,
                "location_name" => fake()->text(),
                "location_type" => fake()->text(),
                "source_tariff_db" => null,
                "id_source_tariff" => null,
                "pod" => null,
                "history" => null
            ],
            "origin_data" => [
                "customer_name" => fake()->name(),
                "customer_address" => fake()->address(),
                "customer_email" => fake()->safeEmail(),
                "customer_phone" => fake()->phoneNumber(),
                "customer_zip_code" => fake()->text(),
                "zone_code" => fake()->text(),
                "customer_address_detail" => fake()->address(),
            ],
            "destination_data" => [
                "customer_name" => fake()->name(),
                "customer_address" => fake()->address(),
                "customer_email" => fake()->safeEmail(),
                "customer_phone" => fake()->phoneNumber(),
                "customer_zip_code" => fake()->text(),
                "zone_code" => fake()->text(),
                "customer_address_detail" => fake()->address(),
            ],
            "koli_data" => [
                [
                    "koli_length" => 0,
                    "awb_url" => fake()->url(),
                    "koli_chargeable_weight" => 0,
                    "koli_width" => 0,
                    "koli_surcharge" => null,
                    "koli_height" => 0,
                    "koli_description" => fake()->text(),
                    "koli_formula_id" => null,
                    "koli_volume" => 0,
                    "koli_weight" => 0,
                    "koli_custom_field" => null,
                ]
            ],
            "custom_field" => null,
            "currentLocation" => [
                "name" => fake()->name(),
                "code" => fake()->text(),
                "type" => fake()->text(),
            ]
        ];
    }
    /**
     * A basic feature test example.
     */
    public function test_it_can_store_package(): void
    {
        $requestData = $this->createRequestData();
        $packageData = Package::factory()->create([
            "customer_name" => $requestData["customer_name"],
            "customer_code" => $requestData["customer_code"],
            "transaction_amount" => $requestData["transaction_amount"],
            "transaction_discount" => $requestData["transaction_discount"],
            "transaction_additional_field" => $requestData["transaction_additional_field"],
            "transaction_payment_type" => $requestData["transaction_payment_type"],
            "transaction_state" => $requestData["transaction_state"],
            "transaction_code" => $requestData["origin_data"]["zone_code"].Carbon::now()->format("Ymd")."1",
            "transaction_order" => 1,
            "location_id" => $requestData["location_id"],
            "organization_id" => $requestData["organization_id"],
            "transaction_payment_type_name" => $requestData["transaction_payment_type_name"],
            "transaction_cash_amount" => $requestData["transaction_cash_amount"],
            "transaction_cash_change" => $requestData["transaction_cash_change"],
            "customer_attribute" => $requestData["customer_attribute"],
            "origin_data" => $requestData["origin_data"],
            "destination_data" => $requestData["destination_data"],
            "custom_field" => $requestData["custom_field"],
            "currentLocation" => $requestData["currentLocation"],
        ]);

        $connoteData = Connote::factory()->create([
            "connote_number" => $requestData["connote"]["connote_number"],
            "connote_service" => $requestData["connote"]["connote_service"],
            "connote_service_price" => intval($requestData["transaction_amount"]),
            "connote_amount" => intval($requestData["transaction_amount"])-intval($requestData["transaction_discount"]),
            "connote_code" => "AWB00010002".Carbon::now()->format("dmY").(Connote::count()+1),
            "connote_booking_code" => $requestData["connote"]["connote_booking_code"],
            "connote_order" => $requestData["connote"]["connote_order"],
            "connote_state_id" => $requestData["transaction_state"],
            "zone_code_from" => $requestData["origin_data"]["zone_code"],
            "zone_code_to" => $requestData["destination_data"]["zone_code"],
            "surcharge_amount" => $requestData["connote"]["surcharge_amount"],
            "transaction_id" => $packageData->transaction_id,
            "actual_weight" => $requestData["connote"]["actual_weight"],
            "volume_weight" => $requestData["connote"]["volume_weight"],
            "chargeable_weight" => $requestData["connote"]["chargeable_weight"],
            "organization_id" => $requestData["organization_id"],
            "location_id" => $requestData["location_id"],
            "connote_total_package" => $requestData["connote"]["connote_total_package"],
            "connote_surcharge_amount" => $requestData["connote"]["connote_surcharge_amount"],
            "connote_sla_day" => $requestData["connote"]["connote_sla_day"],
            "location_name" => $requestData["connote"]["location_name"],
            "location_type" => $requestData["connote"]["location_type"],
            "source_tariff_db" => $requestData["connote"]["source_tariff_db"],
            "id_source_tariff" => $requestData["connote"]["id_source_tariff"],
            "pod" => $requestData["connote"]["pod"],
            "history" => $requestData["connote"]["history"],
        ]);

        $koli = $requestData["koli_data"][0];
        $koliData = Koli::factory()->create([
            "koli_length" => $koli["koli_length"],
                "awb_url" => $koli["awb_url"],
                "koli_chargeable_weight" => $koli["koli_chargeable_weight"],
                "koli_width" => $koli["koli_width"],
                "koli_surcharge" => $koli["koli_surcharge"],
                "koli_height" => $koli["koli_height"],
                "koli_description" => $koli["koli_description"],
                "koli_formula_id" => $koli["koli_formula_id"],
                "connote_id" => $connoteData->connote_id,
                "koli_volume" => $koli["koli_volume"],
                "koli_weight" => $koli["koli_weight"],
                "koli_custom_field" => $koli["koli_custom_field"],
                "koli_code" => $connoteData->connote_code.".1",
        ]);
        $this->service->shouldReceive("store")->andReturn($packageData);


        $response = $this->postJson(data:$requestData,uri:route('storePackage'),headers:$this->header);

        $response->assertCreated();
        $response->assertJsonStructure([
            'message',
            'data' => [
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
                'customer_attribute' => [
                    "Nama_Sales",
                    "TOP",
                    "Jenis_Pelanggan"
                ],
                'connote' => [
                    "connote_number",
                    "connote_service",
                    "connote_service_price",
                    "connote_amount",
                    "connote_code",
                    "connote_booking_code",
                    "connote_order",
                    "connote_state_id",
                    "zone_code_from",
                    "zone_code_to",
                    "surcharge_amount",
                    "transaction_id",
                    "actual_weight",
                    "volume_weight",
                    "chargeable_weight",
                    "organization_id",
                    "location_id",
                    "connote_total_package",
                    "connote_surcharge_amount",
                    "connote_sla_day",
                    "location_name",
                    "location_type",
                    "source_tariff_db",
                    "id_source_tariff",
                    "pod",
                    "history",
                    "connote_id",
                    "updated_at",
                    "created_at",
                    "connote_state",
                ],
                'connote_id',
                'origin_data' => [
                    "customer_name",
                    "customer_address",
                    "customer_email",
                    "customer_phone",
                    "customer_zip_code",
                    "zone_code",
                    "customer_address_detail" ,
                ],
                'destination_data' => [
                    "customer_name",
                    "customer_address",
                    "customer_email",
                    "customer_phone",
                    "customer_zip_code",
                    "zone_code",
                    "customer_address_detail" ,
                ],
                'koli_data' => [
                    [
                        "koli_length",
                        "awb_url",
                        "koli_chargeable_weight",
                        "koli_width",
                        "koli_surcharge",
                        "koli_height",
                        "koli_description",
                        "koli_formula_id",
                        "connote_id",
                        "koli_volume",
                        "koli_weight",
                        "koli_custom_field",
                        "koli_code",
                        "koli_id",
                        "updated_at",
                        "created_at",
                    ]
                ],
                'custom_field',
                'current_location',
            ]
        ]);
    }
    /**
     * A basic feature test example.
     */
    public function test_it_cannot_store_package_if_validation_error(): void
    {
        $requestData = $this->createRequestData();
        unset($requestData["customer_name"]);


        $response = $this->postJson(data:$requestData,uri:route('storePackage'),headers:$this->header);

        $response->assertUnprocessable();
    }
    /**
     * A basic feature test example.
     */
    public function test_it_cannot_store_package_if_some_error_occurs(): void
    {
        $requestData = $this->createRequestData();
        
        $this->service->shouldReceive("store")->andThrow(new Exception("some error occurs"));


        $response = $this->postJson(data:$requestData,uri:route('storePackage'),headers:$this->header);

        $response->assertInternalServerError();
        $response->assertJsonStructure([
            'message',
            'error' 
        ]);
    }
}
