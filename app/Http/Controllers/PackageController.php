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

use function Laravel\Prompts\error;

class PackageController extends Controller
{
    protected PackageService $service;

    public function __construct(PackageService $service) {
        $this->service = $service;
    }
    
    public function getPackage(GetDataRequest $request){
        try {
            $request = $request->validated();
            $data = PackageResource::collection($this->service->getData(
                limit:$request["limit"],
                offset: $request["offset"]
            ));

            return $this->responseSuccess(
                message:'Success getting packages',
                data:$data
            );
        } catch (\Throwable $th) {
            Log::error("failed get data: $th->getMessage");
            return $this->responseError(
                message:'Failed getting packages',
                error:'some error occurs'
            );
        }
    }

    public function getByTransId(String $id){
        try {
            $data = (new PackageResource($this->service->getByTransId($id)));
            return $this->responseSuccess(
                message:'Success getting package',
                data:$data,
            );
        }catch(ModelNotFoundException $e){
            return $this->responseError(
                message:"Failed getting package",
                error:"Package not found",
                code:Response::HTTP_NOT_FOUND
            );
        } 
        catch (\Throwable $th) {
            Log::error("failed delete data: $th->getMessage");
            return $this->responseError(
                message:"Failed getting package",
                error:"Some error occurs",
                code:Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    public function store(StoreRequest $request){
        try {
            $data = (new PackageResource($this->service->store($request->validated())));
            return $this->responseSuccess(
                message:'Success storing package',
                data:$data,
                code:Response::HTTP_CREATED
            );
        } catch (\Throwable $th) {
            Log::error("failed get data: $th->getMessage");
            return $this->responseError(
                message:'Failed inserting pacckage',
                error:'some error occurs'
            );
        }
    }

    public function update($request, String $id){

    }

    public function replace($request, String $id){
        
    }

    public function delete(String $id){
        try {
            $this->service->deletePackage($id);
            return $this->responseSuccess(
                message:'Success deleting package',
                code:Response::HTTP_OK,
                data:null
            );
            
        }catch(ModelNotFoundException $e){
            return $this->responseError(
                message:"Failed deleting package",
                error:"Package not found",
                code:Response::HTTP_NOT_FOUND
            );
        } 
        catch (\Throwable $th) {
            Log::error("failed delete data: $th->getMessage");
            return $this->responseError(
                message:"Failed deleting package",
                error:"Some error occurs",
                code:Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    private function responseError(String $message, String $error, int $code = Response::HTTP_INTERNAL_SERVER_ERROR){
        $responseApi = [
            'message'   => $message,
            'error' => $error
        ];
        return response()->json($responseApi,$code);
    }

    private function responseSuccess(String $message, $data, int $code= Response::HTTP_OK){
        $responseApi = [
            'message'   =>$message,
        ];

        if($data) $responseApi['data'] = $data;
        return response()->json($responseApi,$code);
    }
}
