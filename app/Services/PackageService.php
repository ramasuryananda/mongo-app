<?php

namespace App\Services;

use Error;
use Carbon\Carbon;
use App\Models\Connote;
use App\Models\Package;
use App\Models\Packages;
use Illuminate\Http\Request;
use App\Repository\KoliRepository;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StoreRequest;
use App\Repository\ConnoteRepository;
use App\Repository\PackageRepository;
use Exception;
use Illuminate\Database\Eloquent\Collection;

class PackageService
{

    protected PackageRepository $packageRepository;
    protected ConnoteRepository $connoteRepository;
    protected KoliRepository $koliRepository;

    public function __construct(
        PackageRepository $packageRepository,
        ConnoteRepository $connoteRepository,
        KoliRepository $koliRepository
        ) {
        $this->packageRepository = $packageRepository;
        $this->connoteRepository = $connoteRepository;
        $this->koliRepository = $koliRepository;
    }

    function Store(array $requestData): Package
    {
        $state = $requestData["transaction_state"];

        $orderCount = $this->packageRepository->getCount()+1;
        $transactionCode = $requestData["origin_data"]["zone_code"].Carbon::now()->format("Ymd").$orderCount;

        $packageData = $this->packageRepository->store([
            "customer_name" => $requestData["customer_name"],
            "customer_code" => $requestData["customer_code"],
            "transaction_amount" => $requestData["transaction_amount"],
            "transaction_discount" => $requestData["transaction_discount"],
            "transaction_additional_field" => $requestData["transaction_additional_field"],
            "transaction_payment_type" => $requestData["transaction_payment_type"],
            "transaction_state" => $state,
            "transaction_code" => $transactionCode,
            "transaction_order" => $orderCount,
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

        $serviceAmount = intval($requestData["transaction_amount"]);
        $serviceDiscount = intval($requestData["transaction_discount"]);
        $totalAmount = $serviceAmount-$serviceDiscount;

        $connoteData = $this->connoteRepository->store([
            "connote_number" => $requestData["connote"]["connote_number"],
            "connote_service" => $requestData["connote"]["connote_service"],
            "connote_service_price" => $serviceAmount,
            "connote_amount" => $totalAmount,
            "connote_code" => "AWB00010002".Carbon::now()->format("dmY").(Connote::count()+1),
            "connote_booking_code" => $requestData["connote"]["connote_booking_code"],
            "connote_order" => $requestData["connote"]["connote_order"],
            "connote_state_id" => $state,
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
        $count = 0;
        foreach ($requestData["koli_data"] as $key => $koli) {
            $count++;
            $this->koliRepository->store([
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
                "koli_code" => $connoteData->connote_code.".$count",
            ]);
        }
        return $packageData;
    }
    
    function replace(array $requestData, String $id): Package
    {
        $state = $requestData["transaction_state"];

        $orderCount = $this->packageRepository->getCount()+1;
        $transactionCode = $requestData["origin_data"]["zone_code"].Carbon::now()->format("Ymd").$orderCount;

        $this->packageRepository->replace([
            "customer_name" => $requestData["customer_name"],
            "customer_code" => $requestData["customer_code"],
            "transaction_amount" => $requestData["transaction_amount"],
            "transaction_discount" => $requestData["transaction_discount"],
            "transaction_additional_field" => $requestData["transaction_additional_field"],
            "transaction_payment_type" => $requestData["transaction_payment_type"],
            "transaction_state" => $state,
            "transaction_code" => $transactionCode,
            "transaction_order" => $orderCount,
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
        ],$id);

        $packageData = $this->packageRepository->getByTransId($id);

        $this->koliRepository->deleteByConnoteId($packageData->connote->connote_id);
        $this->connoteRepository->deleteByTransId($packageData->transaction_id);

        $serviceAmount = intval($requestData["transaction_amount"]);
        $serviceDiscount = intval($requestData["transaction_discount"]);
        $totalAmount = $serviceAmount-$serviceDiscount;
        $connoteData = $this->connoteRepository->store([
            "connote_number" => $requestData["connote"]["connote_number"],
            "connote_service" => $requestData["connote"]["connote_service"],
            "connote_service_price" => $serviceAmount,
            "connote_amount" => $totalAmount,
            "connote_code" => "AWB00010002".Carbon::now()->format("dmY").(Connote::count()+1),
            "connote_booking_code" => $requestData["connote"]["connote_booking_code"],
            "connote_order" => $requestData["connote"]["connote_order"],
            "connote_state_id" => $state,
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
        ],$id);

        $count = 0;
        foreach ($requestData["koli_data"] as $key => $koli) {
            $count++;
            $this->koliRepository->store([
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
                "koli_code" => $connoteData->connote_code.".$count",
            ]);
        }
        return $packageData;
    }

    function update(array $data,String $id): Package{
        $this->packageRepository->update([
            "transaction_state" => $data["transaction_state"],
            "location_id" => $data["location_id"],
            "currentLocation" => $data["currentLocation"]
        ],$id);
        
        $this->connoteRepository->updateFromTransaction([
            "location_id" => $data["location_id"],
            "connote_state_id" => $data["transaction_state"],
            "location_name" => $data["currentLocation"]["name"]
        ],$id);

        return $this->packageRepository->getByTransId($id);
    }

    function getData(?int $offset, ?int $limit):Collection{
        return $this->packageRepository->getAll(
            limit:$limit,
            offset:$offset
        );
    }

    function getByTransId(String $id):Package{
        return $this->packageRepository->getByTransId($id);
    }

    function deletePackage(String $id){
        $packageData = $this->packageRepository->getByTransId($id);
        if($packageData->connote){
            $this->koliRepository->deleteByConnoteId($packageData->connote->connote_id);
            $this->connoteRepository->deleteByTransId($id);
        }
        return $this->packageRepository->deleteByTransId($id);
    }
}
