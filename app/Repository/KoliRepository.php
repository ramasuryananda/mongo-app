<?php

namespace App\Repository;

use App\Http\Requests\StoreRequest;
use App\Models\Connote;
use App\Models\Koli;

class KoliRepository
{
    function store(array $data): Koli
    {
        $connoteData = Koli::create([
            "koli_length" => $data["koli_length"],
            "awb_url" => $data["awb_url"],
            "koli_chargeable_weight" => $data["koli_chargeable_weight"],
            "koli_width" => $data["koli_width"],
            "koli_surcharge" => $data["koli_surcharge"],
            "koli_height" => $data["koli_height"],
            "koli_description" => $data["koli_description"],
            "koli_formula_id" => $data["koli_formula_id"],
            "connote_id" => $data["connote_id"],
            "koli_volume" => $data["koli_volume"],
            "koli_weight" => $data["koli_weight"],
            "koli_custom_field" => $data["koli_custom_field"],
            "koli_code" => $data["koli_code"],
        ]);
        return $connoteData;
    }
}
