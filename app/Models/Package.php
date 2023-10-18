<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;


class Package extends Model
{
    use HasFactory,HasUuids;
    protected $connection = "mongodb";
    protected $collection = "packages";
    protected $primaryKey = "transaction_id";

    public $fillable = [
        "customer_name",
        "transaction_amount",
        "transaction_discount",
        "transaction_discount",
        "transaction_additional_field",
        "transaction_payment_type",
        "transaction_state",
        "transaction_code",
        "transaction_order",
        "location_id",
        "organization_id",
        "transaction_payment_type_name",
        "transaction_cash_amount",
        "transaction_cash_change",
        "customer_attribute",
        "connote_id",
        "origin_data",
        "destination_data",
        "koli_data",
        "custom_field",
        "currentLocation"
    ];

    public function connote(){
        return $this->hasOne(Connote::class);
    }
}