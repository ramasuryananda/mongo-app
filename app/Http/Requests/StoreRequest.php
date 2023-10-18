<?php

namespace App\Http\Requests;

use App\Models\Package;
use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {

        $transactionState = implode(",",array_keys(Package::STATE));
        
        $rules = [
            "customer_name" => 'required|min:1',
            "customer_code"=> 'required|min:1',
            "transaction_amount" => 'required|numeric',
            "transaction_discount" => 'required',
            "transaction_additional_field" => 'nullable',
            "transaction_payment_type" => 'required|numeric',
            "transaction_state" => "required|numeric|in:$transactionState",
            "location_id" => 'required|string',
            "organization_id" => 'required|numeric',
            "transaction_payment_type_name" => 'required|string',
            "transaction_cash_amount" => 'required|numeric',
            "transaction_cash_change" => 'required|numeric',
            "customer_attribute" => 'required|array:Nama_Sales,TOP,Jenis_Pelanggan',
            "customer_attribute.Nama_Sales" => "required|string",
            "customer_attribute.TOP" => 'required|string',
            "customer_attribute.Jenis_Pelanggan" => 'required|string',
            "connote" => "required|array",
            "connote.connote_number" => 'required|numeric',
            "connote.connote_service" => 'required|string',
            "connote.connote_booking_code" => 'nullable|string',
            "connote.connote_order" => 'required|numeric',
            "connote.zone_code_from" => 'required|string',
            "connote.zone_code_to" => 'required|string',
            "connote.surcharge_amount" => 'nullable|numeric',
            "connote.actual_weight" => 'required|numeric|min:0',
            "connote.volume_weight" => 'required|numeric|min:0',
            "connote.chargeable_weight" => 'required|numeric|min:0',
            "connote.connote_total_package" => 'required|numeric|min:0',
            "connote.connote_surcharge_amount" => 'required|numeric|min:0',
            "connote.connote_sla_day" => 'required|numeric|min:0',
            "connote.location_name" => 'required|string',
            "connote.source_tariff_db" => 'required|string',
            "connote.id_source_tariff" => 'required|numeric',
            "connote.location_type" => 'required|string',
            "connote.pod" => 'nullable',
            "connote.history" => 'nullable|array',
            "origin_data" => 'required|array',
            "origin_data.customer_name"=> 'required|string',
            "origin_data.customer_address"=> 'required|string',
            "origin_data.customer_email"=> 'nullable|string|email',
            "origin_data.customer_phone"=> 'required|string',
            "origin_data.customer_zip_code"=> 'required|string',
            "origin_data.zone_code"=> 'required|string',
            "origin_data.customer_address_detail"=> 'nullable',
            "destination_data" => 'required|array',
            "destination_data.customer_name"=> 'required|string',
            "destination_data.customer_address"=> 'required|string',
            "destination_data.customer_email"=> 'nullable|string|email',
            "destination_data.customer_phone"=> 'required|string',
            "destination_data.customer_zip_code"=> 'required|string',
            "destination_data.zone_code"=> 'required|string',
            "destination_data.customer_address_detail"=> 'nullable',
            "koli_data" => "nullable|array",
            "koli_data.*" => 'nullable|array',
            "koli_data.*.koli_length" => 'required|numeric',
            "koli_data.*.awb_url" => 'required|string',
            "koli_data.*.koli_chargeable_weight" => 'required|numeric',
            "koli_data.*.koli_width" => 'required|numeric',
            "koli_data.*.koli_surcharge" => 'nullable|array',
            "koli_data.*.koli_height" => 'required|numeric',
            "koli_data.*.koli_description" => 'required|string',
            "koli_data.*.koli_formula_id" => 'nullable',
            "koli_data.*.koli_volume" => 'required|numeric',
            "koli_data.*.koli_weight" => 'required|numeric',
            "koli_data.*.koli_custom_field" => 'required|array',
            "custom_field" => "nullable|array",
            "currentLocation" => "required|array:name,code,type",
            "currentLocation.name" => "required|string",
            "currentLocation.code" => "required|string",
            "currentLocation.type" => "required|string",
        ];

        return $rules;
    }
}
