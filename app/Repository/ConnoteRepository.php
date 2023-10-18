<?php

namespace App\Repository;

use App\Http\Requests\StoreRequest;
use App\Models\Connote;
use App\Models\Package;
use App\Models\Packages;
use Error;
use Illuminate\Http\Request;

class ConnoteRepository
{
    function store(array $data): Connote
    {
        $connoteData = Connote::create([
            "connote_number" => $data["connote_number"],
            "connote_service" => $data["connote_service"],
            "connote_service_price" => $data["connote_service_price"],
            "connote_amount" => $data["connote_amount"],
            "connote_code" => $data["connote_code"],
            "connote_booking_code" => $data["connote_booking_code"],
            "connote_order" => $data["connote_order"],
            "connote_state_id" => $data["connote_state_id"],
            "zone_code_from" => $data["zone_code_from"],
            "zone_code_to" => $data["zone_code_to"],
            "transaction_id" => $data["transaction_id"],
            "actual_weight" => $data["actual_weight"],
            "volume_weight" => $data["volume_weight"],
            "chargeable_weight" => $data["chargeable_weight"],
            "organization_id" => $data["organization_id"],
            "location_id" => $data["location_id"],
            "connote_total_package" => $data["connote_total_package"],
            "connote_surcharge_amount" => $data["connote_surcharge_amount"],
            "connote_sla_day" => $data["connote_sla_day"],
            "location_name" => $data["location_name"],
            "location_type" => $data["location_type"],
            "source_tariff_db" => $data["source_tariff_db"],
            "id_source_tariff" => $data["id_source_tariff"],
            "pod" => $data["pod"],
            "history" => $data["history"],
        ]);
        return $connoteData;
    }
}
