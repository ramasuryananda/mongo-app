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

class ReplaceTest extends TestCase
{
    use WithFaker;
    protected $service;
    protected $packageRepo;
    protected $connoteRepo;
    protected $koliRepo;
    protected $package;
    protected $connote;
    protected function setUp():void
    {
        parent::setUp();
        Koli::truncate();
        Connote::truncate();
        Package::truncate();

        $this->package = Package::factory()->create();
        $this->connote = Connote::factory()->create([
            "transaction_id" => $this->package->transaction_id
        ]);

        $this->packageRepo = $this->mock(PackageRepository::class);
        $this->connoteRepo = $this->mock(ConnoteRepository::class);
        $this->koliRepo = $this->mock(KoliRepository::class);

        $this->service = $this->app->make(PackageService::class);
    }

    private function createRequestData() : array
    {
        return [
            "customer_name" => fake()->name,
            "customer_code"=> fake()->text(),
            "transaction_amount" => 0,
            "transaction_discount" => 0,
            "transaction_additional_field" => null,
            "transaction_payment_type" => fake()->text(),
            "transaction_state" => 3,
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
                    "koli_surcharge" => 0,
                    "koli_height" => 0,
                    "koli_description" => 0,
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
    public function test_it_can_replace_package(): void
    {
        $requestData = $this->createRequestData();

        $this->packageRepo->shouldReceive("getCount")->andReturn(0);
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
        $this->packageRepo->shouldReceive("getByTransId")->andReturn($this->package);
        $this->koliRepo->shouldReceive("deleteByConnoteId");
        $this->connoteRepo->shouldReceive("deleteByTransId");

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

        $this->packageRepo->shouldReceive("replace")->andReturn(true);
        
        $this->connoteRepo->shouldReceive("store")->andReturn($connoteData);
        $this->koliRepo->shouldReceive("store")->andReturn($koliData);

        $data = $this->service->replace($requestData,$this->package->transaction_id);
        $this->assertNotNull($data->transaction_id);
        $this->assertNotNull($data->connote);
        $this->assertNotNull($data->connote->koli);
    }
}
