<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Koli extends Model
{
    use HasFactory,HasUuids;
    protected $connection = "mongodb";
    protected $collection = "kolis";
    protected $primaryKey = "koli_id";

    public $fillable = [
        "koli_length",
        "awb_url",
        "created_at",
        "koli_chargeable_weight",
        "koli_width",
        "koli_surcharge",
        "koli_height",
        "koli_description",
        "koli_formula_id",
        "connote_id",
        "koli_volume",
        "koli_weight",
        "koli_custom_field",
        "koli_code",
    ];

    public function connote(){
        return $this->belongsTo(Connote::class);
    }
}
