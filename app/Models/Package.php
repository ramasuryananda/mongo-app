<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;
use MongoDB\Laravel\Eloquent\SoftDeletes;

class Package extends Model
{
    use HasFactory,HasUuids,SoftDeletes;
    protected $connection = "mongodb";
    protected $collection = "packages";
    protected $primaryKey = "transaction_id";

    const STATE = [
        0 => "FAILED",
        1 => "PENDING",
        2 => "PAID",
        3 => "SUCCESS"
    ];

    public $fillable = [
        "customer_name",
        "customer_code",
        "transaction_amount",
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
        "origin_data",
        "destination_data",
        "custom_field",
        "currentLocation"
    ];

    public function connote(){
        return $this->hasOne(Connote::class,"transaction_id","transaction_id");
    }
}
