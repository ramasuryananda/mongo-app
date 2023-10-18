<?php

namespace App\Repository;

use App\Http\Requests\StoreRequest;
use App\Models\Package;
use App\Models\Packages;
use Error;
use Illuminate\Http\Request;

class PackageRepository
{
    function store(array $data): Package
    {
        $packageData = Package::create([
            "customer_name" => $data["customer_name"],
            "transaction_amount" => $data["transaction_amount"],
            "transaction_discount" => $data["transaction_discount"],
            "transaction_additional_field" => $data["transaction_additional_field"],
            "transaction_payment_type" => $data["transaction_payment_type"],
            "transaction_state" => $data["transaction_state"],
            "transaction_code" => $data["transaction_code"],
            "transaction_order" => $data["transaction_order"],
            "location_id" => $data["location_id"],
            "organization_id" => $data["organization_id"],
            "transaction_payment_type_name" => $data["transaction_payment_type_name"],
            "transaction_cash_amount" => $data["transaction_cash_amount"],
            "transaction_cash_change" => $data["transaction_cash_change"],
            "customer_attribute" => $data["customer_attribute"],
            "origin_data" => $data["origin_data"],
            "destination_data" => $data["destination_data"],
            "custom_field" => $data["custom_field"],
            "currentLocation" => $data["currentLocation"],
        ]);
        return $packageData;
    }

    function getCount():int
    {
        return Package::count();
    }
}
