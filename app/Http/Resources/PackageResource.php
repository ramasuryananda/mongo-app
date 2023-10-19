<?php

namespace App\Http\Resources;

use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PackageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        $connote = $this->connote;
        $connote->connote_state = Package::STATE[$connote->connote_state_id];
        $connote = $connote->makeHidden(['koli']);
        return [
            'transaction_id' => $this->transaction_id,
            'customer_name' => $this->customer_name,
            'customer_code' => $this->customer_code,
            'transaction_amount' => $this->transaction_amount,
            'transaction_discount' => $this->transaction_discount,
            'transaction_additional_field' => $this->transaction_additional_field,
            'transaction_payment_type' => $this->transaction_payment_type,
            'transaction_state' => Package::STATE[$this->transaction_state]??"-",
            'transaction_code' => $this->transaction_code,
            'transaction_order'=> $this->transaction_order,
            'location_id' => $this->location_id,
            'organization_id' => $this->organization_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'transaction_payment_type_name' => $this->transaction_payment_type_name,
            'transaction_cash_amount' => $this->transaction_cash_amount,
            'transaction_cash_change' => $this->transaction_cash_change,
            'customer_attribute' => $this->customer_attribute,
            'connote' => $connote,
            'connote_id' => $connote->id,
            'origin_data' => $this->origin_data,
            'destination_data' => $this->destination_data,
            'koli_data' => $this->connote->koli,
            'custom_field' => $this->custom_field,
            'current_location' => $this->current_location
        ];
    }
}
