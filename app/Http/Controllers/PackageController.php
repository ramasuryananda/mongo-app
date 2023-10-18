<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetDataRequest;
use App\Http\Requests\StoreRequest;
use App\Http\Resources\PackageResource;
use App\Models\Package;
use App\Services\PackageService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use PhpParser\Node\Expr\Instanceof_;

class PackageController extends Controller
{
    protected PackageService $service;

    public function __construct(PackageService $service) {
        $this->service = $service;
    }
    function getPackage(GetDataRequest $request){
        try {
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
        } catch (\Throwable $th) {
            Log::error("failed get data: $th->getMessage");
            $responseApi = [
                'message'   => 'Failed getting packages',
                'error' => 'some error occurs.'
            ];
            return response()->json($responseApi,Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    function getByTransId(String $id){
        try {
            $data = $this->service->getByTransId($id);

            $responseApi = [
                'message'   => 'Success getting package',
                'data'      => (New PackageResource($data)),
            ];
            return response()->json($responseApi,Response::HTTP_OK);
        }catch(ModelNotFoundException $e){
            $responseApi = [
                'message'   => 'Failed getting package',
                'error' => 'Data not found'
            ];
            return response()->json($responseApi,Response::HTTP_NOT_FOUND);
        } 
        catch (\Throwable $th) {
            Log::error("failed get data: $th->getMessage");
            $responseApi = [
                'message'   => 'Failed getting package',
                'error' => 'Some error occurs.'
            ];
            return response()->json($responseApi,Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    function store(StoreRequest $request){
        
        $data = $this->service->store($request->validated());

        $responseApi = [
            'message'   => 'Success inserting package',
            'data'      => (new PackageResource($data)),
        ];
        return response()->json($responseApi,Response::HTTP_CREATED);
    }

    function update($request, String $id){

    }

    function replace($request, String $id){
        
    }

    function delete(String $id){

    }
}
