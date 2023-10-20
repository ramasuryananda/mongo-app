<?php

namespace App\Repository;

use App\Http\Requests\StoreRequest;
use App\Models\Package;
use App\Models\Packages;
use Error;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class PackageRepository
{
    function store(array $data): Package
    {
        $packageData = Package::create([
            "customer_name" => $data["customer_name"],
            "customer_code" => $data["customer_code"],
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
    
    function replace(array $data,String $id): bool
    {
        return Package::findOrFail($id)->update([
            "customer_name" => $data["customer_name"],
            "customer_code" => $data["customer_code"],
            "transaction_amount" => $data["transaction_amount"],
            "transaction_discount" => $data["transaction_discount"],
            "transaction_additional_field" => $data["transaction_additional_field"],
            "transaction_payment_type" => $data["transaction_payment_type"],
            "transaction_state" => $data["transaction_state"],
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
    }

    function getAll(?int $offset, ?int $limit):Collection{
        return Package::with(["connote.koli"])->offset($offset)->limit($limit)->orderBy("created_at","DESC")->get();
    }

    function getByTransId(String $id){
        return Package::with(["connote.koli"])->findOrFail($id);
    }

    function deleteByTransId(String $id){
        return Package::findOrFail($id)->delete();
    }

    function getCount():int
    {
        return Package::withTrashed()->count();
    }

    function update(array $data, String $id):bool{
        return Package::findOrFail($id)->update([
            "transaction_state" => $data["transaction_state"],
            "location_id" => $data["location_id"],
            "currentLocation" => $data["currentLocation"]
        ]);
    }
}
