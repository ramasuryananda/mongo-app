<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRequest;
use App\Http\Resources\PackageResource;
use App\Services\PackageService;

class PackageController extends Controller
{
    protected PackageService $service;

    public function __construct(PackageService $service) {
        $this->service = $service;
    }
    function getPackage(){
        //TODO:make get package data
        
    }

    function getByTransId(String $id){

    }

    function store(StoreRequest $request){
        
        $data = $this->service->store($request->validated());

        $responseApi = [
            'data'      => (new PackageResource($data)),
            'message'   => 'Success inserting package'
        ];
        return response()->json($responseApi,201);
    }

    function update($request){

    }

    function replace($request){
        
    }

    function delete(String $id){

    }
}
