<?php

namespace Tests\Unit\Repository\Koli;

use App\Models\Connote;
use App\Models\Koli;
use App\Models\Package;
use App\Repository\ConnoteRepository;
use App\Repository\KoliRepository;
use App\Repository\PackageRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Throwable;

class DeleteByConnoteIdTest extends TestCase
{
    use WithFaker;
    protected $repo;
    protected $connote;
    protected function setUp():void
    {
        parent::setUp();
        Package::truncate();
        Connote::truncate();
        Koli::truncate();
        $this->connote = Connote::factory()->create();
        $this->repo = new KoliRepository();
    }
    /**
     * A basic feature test example.
     */
    public function test_it_can_delete_koli_by_connote_id(): void
    {
        Koli::factory()->create(["connote_id" => $this->connote->connote_id]);
        $this->assertCount(1,Koli::all());
        $this->repo->deleteByConnoteId($this->connote->connote_id);
        $this->assertCount(0,Koli::all());
        
    }
}
