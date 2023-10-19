<?php

namespace Tests\Feature\Package;

use Exception;
use Carbon\Carbon;
use Tests\TestCase;
use App\Models\Koli;
use App\Models\Connote;
use App\Models\Package;
use App\Services\PackageService;
use App\Repository\KoliRepository;
use Illuminate\Http\Client\Request;
use App\Repository\ConnoteRepository;
use App\Repository\PackageRepository;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Request as FacadesRequest;

class DeleteTest extends TestCase
{
    use WithFaker;
    protected $service;
    protected $package;
    protected $connote;
    protected $koli;
    protected $header;
    protected function setUp():void
    {
        parent::setUp();
        Koli::truncate();
        Connote::truncate();
        Package::truncate();

        $this->service = $this->mock(PackageService::class);

        $this->header = [
            'Content-type'  => 'application/json',
            'Accept'        => 'application/json',
        ];
    }
    /**
     * A basic feature test example.
     */
    public function test_it_can_delete_package(): void
    {
        $packageData = Package::factory()->create();
        $this->service->shouldReceive("deletePackage");

        $url = route("deletePackage",$packageData->transaction_id);

        $response = $this->deleteJson(uri:$url,headers:$this->header);

        $response->assertOk();
        $response->assertJsonStructure([
            'message',
        ]);
    }
    /**
     * A basic feature test example.
     */
    public function test_it_cannot_delete_package_if_some_error_occurs(): void
    {
        $packageData = Package::factory()->create();
        
        $this->service->shouldReceive("deletePackage")->andThrow(new Exception("some error occurs"));


        $response = $this->deleteJson(uri:route('deletePackage',$packageData->transaction_id),headers:$this->header);

        $response->assertInternalServerError();
        $response->assertJsonStructure([
            'message',
            'error' 
        ]);
    }

    public function test_it_cannot_delete_package_if_not_found(): void
    {
        $packageData = Package::factory()->create();
        
        $this->service->shouldReceive("deletePackage")->andThrow(ModelNotFoundException::class);


        $response = $this->deleteJson(uri:route('deletePackage',$packageData->transaction_id),headers:$this->header);

        $response->assertNotFound();
        $response->assertJsonStructure([
            'message',
            'error' 
        ]);
    }
}
