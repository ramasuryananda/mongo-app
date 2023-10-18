<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetDataRequest;
use App\Http\Requests\StoreRequest;
use App\Http\Resources\PackageResource;
use App\Models\Package;
use App\Services\PackageService;
use Illuminate\Http\Response;

class PackageController extends Controller
{
    protected PackageService $service;

    public function __construct(PackageService $service) {
        $this->service = $service;
    }
    function getPackage(GetDataRequest $request){
        $request = $request->validated();
        $data = PackageResource::collection($this->service->getData(
            limit:$request["limit"],
            offset: $request["offset"]
        ));

        $responseApi = [
            'message'   => 'Success getting packages',
            'data'      => $data,
        ];
        return response()->json($responseApi,Response::HTTP_OK);
    }

    function getByTransId(String $id){
    }

    function store(StoreRequest $request){
        
        $data = $this->service->store($request->validated());

        $responseApi = [
            'message'   => 'Success inserting package',
            'data'      => (new PackageResource($data)),
        ];
        return response()->json($responseApi,Response::HTTP_CREATED);
    }

    function update($request){

    }

    function replace($request){
        
    }

    function delete(String $id){

    }
}
