<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;

class Connote extends Model
{
    use HasFactory,HasUuids;
    protected $connection = "mongodb";
    protected $collection = "connotes";
    protected $primaryKey = "connote_id";

    const STATE = [
        0 => "PENDING",
        1 => "FAILED",
        2 => "PAID"
    ];

    public $fillable = [
        "connote_number",
        "connote_service",
        "connote_service_price",
        "connote_amount",
        "connote_code",
        "connote_booking_code",
        "connote_order",
        "connote_state_id",
        "zone_code_from",
        "zone_code_to",
        "transaction_id",
        "actual_weight",
        "volume_weight",
        "chargeable_weight",
        "organization_id",
        "location_id",
        "connote_total_package",
        "connote_surcharge_amount",
        "connote_sla_day",
        "location_name",
        "location_type",
        "source_tariff_db",
        "id_source_tariff",
        "pod",
        "history"
    ];

    public function package(){
        return $this->belongsTo(Package::class,"transaction_id","transaction_id");
    }

    public function koli(){
        return $this->hasMany(Koli::class,"connote_id","connote_id");
    }
}
