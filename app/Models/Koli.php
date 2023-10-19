<?php

namespace App\Models;

use Database\Factories\KoliFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use MongoDB\Laravel\Eloquent\Model as EloquentModel;

class Koli extends EloquentModel
{
    use HasFactory,HasUuids;
    protected $connection = "mongodb";
    protected $collection = "kolis";
    protected $primaryKey = "koli_id";

    public $fillable = [
        "koli_length",
        "awb_url",
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
        return $this->belongsTo(Connote::class,"connote_id","connote_id");
    }

    protected static function newFactory(): Factory
    {
        return KoliFactory::new();
    }
}
