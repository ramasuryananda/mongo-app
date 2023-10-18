<?php

namespace App\Http\Requests;

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
        $rules = [
            "customer_name" => 'required|min:1',
            "customer_code"=> 'required|min:1',
            "transaction_amount" => 'required|numeric',
            "transaction_discount" => 'required',
            "transaction_additional_field" => 'nullable',
            "transaction_payment_type" => 'required|numeric',
            "transaction_state" => 'required|string',
            "transaction_code" => 'required|unique:packages,transaction_code',
            "transaction_order" => 'required|numeric',
            "location_id" => 'required|string',
            "organization_id" => 'required|numeric',
            "transaction_payment_type_name" => 'required|string',
            "transaction_cash_amount" => 'required|numeric',
            "transaction_cash_change" => 'required|numeric',
            "customer_attribute" => 'required|array:Nama_Sales,TOP,Jenis_Pelanggan|min:1|max:1',
            "customer_attribute.Name_Sales" => "required|string",
            "customer_attribute.TOP" => 'required|string',
            "customer_attribute.Jenis_Pelanggan" => 'required|string',
            "connote" => "required|array:connote_number,connote_service,connote_code"
        ];

        return [
            //
        ];
    }
}
