<?php

namespace Tests\Unit\Http\Controllers;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Http\Controllers\DashboardController;
use Mockery as m;
use App\Models\User;
use App\Repositories\RepositoryInterface\BorrowRepositoryInterface;
use App\Repositories\RepositoryInterface\UserRepositoryInterface;
use Response;

class DashboardControllerTest extends TestCase
{
    protected $userRepositoryMock;
    protected $borowRepositoryMock;

    public function setUp(): void
    {
        $this->afterApplicationCreated(function () {
            $this->userRepositoryMock = m::mock($this->app->make(UserRepositoryInterface::class));
            $this->borowRepositoryMock = m::mock($this->app->make(BorrowRepositoryInterface::class));
        });
        parent::setUp();
    }

    public function test_return()
    {
        $controller = new DashboardController($this->borowRepositoryMock, $this->userRepositoryMock);
        $test = $controller->userCountChart();
        $this->assertNotNull($test);
    }   

    public function test_json()
    {
        $this->withoutMiddleware();
        
        $content = $this->call('GET', '/admin/user-count-chart')->getContent();
        $res_array = (array)json_decode($content);
        $this->assertArrayHasKey('data', $res_array);
    }
}
