<?php

namespace App\Http\Requests;

use App\Models\Package;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
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
            "transaction_state" => "required|in:$transactionState|numeric",
            "location_id" => "nullable|string",
            "currentLocation" => "required_with:location_id|array:name,code,type",
            "currentLocation.name" => "required|string",
            "currentLocation.code" => "required|string",
            "currentLocation.type" => "required|string",
        ];
        return $rules;
    }
}
