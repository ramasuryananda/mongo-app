<?php

namespace Tests\Feature\Unit\Repository\Connote;

use App\Models\Connote;
use App\Models\Package;
use App\Repository\ConnoteRepository;
use App\Repository\PackageRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Throwable;

class DeleteByTransIdTest extends TestCase
{
    use WithFaker;
    protected $repo;
    protected $connote;
    protected function setUp():void
    {
        parent::setUp();
        Package::truncate();
        Connote::truncate();
        $this->connote = Connote::factory()->create();
        $this->repo = new ConnoteRepository();
    }
    /**
     * A basic feature test example.
     */
    public function test_it_can_delete_connote_by_trans_id(): void
    {
        $this->assertCount(1,Connote::all());
        $this->repo->deleteByTransId($this->connote->transaction_id);
        $this->assertCount(0,Connote::all());
        
    }
}
